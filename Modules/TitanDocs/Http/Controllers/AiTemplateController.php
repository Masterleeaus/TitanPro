<?php

namespace Modules\TitanDocs\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\TitanDocs\Entities\AiTemplate;
use Modules\TitanDocs\Entities\AiPromptHistory;
use Modules\TitanDocs\Entities\AiTemplateLanguage;
use Modules\TitanDocs\Entities\AiTemplateCategory;
use Modules\TitanDocs\Entities\AiTemplatePrompt;
use Modules\TitanDocs\Entities\AiPromptResponse;
use Modules\TitanDocs\Events\UpdateHistory;
use Modules\TitanCore\Contracts\AI\ClientInterface as TitanClient;

class AiTemplateController extends AccountBaseController
{
    protected function getProfileNameByCategory($categoryId)
    {
        $defaultProfile = 'office';
        $category = $categoryId ? AiTemplateCategory::find($categoryId) : null;
        $map = config('titandocs.titan_core.category_profiles', []);

        if ($category && isset($map[$category->name])) {
            return $map[$category->name];
        }

        return $defaultProfile;
    }

    protected function getProfileConfig($profile)
    {
        $config = config('titandocs.titan_core.profiles');

        if (is_array($config) && isset($config[$profile])) {
            return $config[$profile];
        }

        // Fallback to SWMS if available, else an empty array
        if (is_array($config) && isset($config['swms'])) {
            return $config['swms'];
        }

        return [];
    }

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('Titan Docs');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($this->userCan('ai document manage'))
        {
            $template=AiTemplate::where('status',true)->orderBy('id', 'asc')->get();
            $content = AiTemplate::where('category_id', '1')->where('status', true)->get();
            $blog = AiTemplate::where('category_id', '2')->where('status', true)->get();
            $website = AiTemplate::where('category_id', '3')->where('status', true)->get();
            $social = AiTemplate::where('category_id', '4')->where('status', true)->get();
            $email = AiTemplate::where('category_id', '6')->where('status', true)->get();
            $video = AiTemplate::where('category_id', '5')->where('status', true)->get();
            $other = AiTemplate::where('category_id', '7')->where('status', true)->get();

            
            $wizard = null;
            if ($request->filled('wizard')) {
                $wizard = \Modules\TitanDocs\Models\WizardSession::query()
                    ->where('id', $request->wizard)
                    ->where('user_id', auth()->id())
                    ->where('company_id', auth()->user()?->organization_id)
                    ->first();
            }

            return view('titandocs::document.index', compact('template', 'content', 'blog', 'website', 'social', 'email', 'video', 'other' , 'wizard'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if ($this->userCan('ai document create'))
        {
            if(isset($request->template_id) && !empty($request->template_id))
            {
                $document_store=new AiPromptHistory();
                $document_store->template_id=$request->template_id;
                $template = AiTemplate::where('id', $request->template_id)->first();
                if($template)
                {
                    $document_store->doc_name=$template->name."_".time();
                    $document_store->workspace = $this->currentWorkspaceId();
                    // Linkage hooks (optional, may be null if not supplied)
                    if ($request->has('project_id')) {
                        $document_store->project_id = $request->project_id;
                    }
                    if ($request->has('client_id')) {
                        $document_store->client_id = $request->client_id;
                    }
                    $document_store->template_version = $template->version ?? null;
                    $document_store->titan_profile = $this->getProfileNameByCategory($template->category_id ?? null);
                    $profileConfig = $this->getProfileConfig($document_store->titan_profile);
                    $document_store->titan_model = $profileConfig['model'] ?? config('titandocs.titan_core.default_model', 'gpt-5-mini');
                    $document_store->created_by=auth()->user()->id;
                    $document_store->save();


                    return response()->json(['status'=>1,"URL"=>route('titan-docs.document.show',[$document_store->id,$request->template_id])]);
                }
                else{
                    return response()->json(['stutas' =>0,'error' => __('Document does not exist')]);
                }
            }
            else
            {
                 return response()->json(['stutas' =>0,'error' => __('Please select any document')]);
            }
        }
        else
        {
            return response()->json(['stutas' =>0,'error' => __('Permission Denied.')]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($doc_id,$id)
    {

        if ($this->userCan('ai document view'))
        {
            $languages = AiTemplateLanguage::where('status',1)->orderBy('id', 'asc')->get();

            $template = AiTemplate::where('id', $id)->first();



            $html="";
            if($template)
            {

                $history_data=AiPromptResponse::where("created_by",auth()->user()->id)->where("history_prompt_id",$doc_id)->orderBy('id', 'DESC')->get();

                $field_data=json_decode($template->form_fields);

                foreach ($field_data->field as  $value) {
                   $html.='<div class="form-group col-md-12">
                                <label class="form-label ">'.$value->label.'</label>';
                                if($value->field_type=="text_box")
                                {

                                    $html.='<input type="text" class="form-control" name="'.$value->field_name.'" value="" placeholder="'.$value->placeholder.'" required">';
                                }
                                if($value->field_type=="textarea")
                                {
                                    $html.='<textarea type="text" rows=5 class="form-control " id="description" name="'.$value->field_name.'" placeholder="'.$value->placeholder.'" required></textarea>';
                                }
                    $html.='</div>';
                }
                $tones=$this->tone_list();

                $document=AiPromptHistory::where('id',$doc_id)->first();
                if($document){
                    return view('titandocs::document.view', compact('languages','template','html','tones','document','history_data'));
                }
                else
                {
                    return redirect()->route('titan-docs.index')->with('error', __('Something went wrong..'));
                }

            }
            else
            {
                return redirect()->back()->with('error', __('Some thing went wronge.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        //return view('titandocs::show');
    }
    public function tone_list()
    {
        $ai_tone=array('funny','casual','excited','professional','witty','sarcastic','feminine','masculine','bold','dramatic','gumpy','secretive');
        return $ai_tone;
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
       return redirect()->back()->with('error', __('Permission Denied.'));
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */

    public function AiGenerate(Request $request)
    {
        if ($request->ajax())
        {
            
            if ($this->userCan('ai document generate'))
            {
                $post=$request->all();
                unset($post['_token']);
                unset($post['creativity']);
                unset($post['max_results']);
                unset($post['words']);
                unset($post['document_id']);
                unset($post['document_name']);
                $data=array();
                $prompt = '';
                $model = '';
                $text = '';
                $ai_token = '';
                $counter = 1;
                $template = AiTemplate::where('template_code', $request->template)->first();

                if (! $template) {
                    return [
                        'status' => 'error',
                        'message' => __('Document does not exist'),
                    ];
                }

                if ($request->template)
                {
                    if ($template && $template->template_code == $request->template)
                    {
                        $required_field=array();
                        $data_field=json_decode($template->form_fields);
                        foreach ($data_field->field as  $val)
                        {
                           request()->validate([$val->field_name => 'required|string']);
                        }

                        $temp_prompt=AiTemplatePrompt::where('template_id',$template->id)->where("created_by",auth()->user()->id)->where('key',$post['language'])->first();
                        if($temp_prompt)
                        {
                            $prompt=$temp_prompt?->value ?? '';
                        }
                        else
                        {
                            $temp_prompt =AiTemplatePrompt::where('template_id',$template->id)->where("created_by",0)->where('key',$post['language'])->first();

                            $prompt=$temp_prompt->value;
                        }

                        foreach ($data_field->field as  $field)
                        {

                            $text_rep="##".$field->field_name."##";
                            if(strpos($prompt,$text_rep) !== false)
                            {
                                $field->value=$post[$field->field_name];
                                $prompt=str_replace($text_rep,$post[$field->field_name],$prompt);
                            }else
                            {
                                $field->value=$post[$field->field_name];
                            }
                            if($template->is_tone==1)
                            {

                                $ai_tone=$post["tone_language"];
                                $param="##tone_language##";
                                $prompt=str_replace($param,$ai_tone,$prompt);
                            }

                        }

                    }
                }
                $ai_token = (int)$request->words;
                $max_results = (int)$request->max_results;
                $ai_creativity = (float)$request->creativity;

                // Determine Titan Core profile based on template category
                $profileName = $this->getProfileNameByCategory($template->category_id ?? null);
                $profileConfig = $this->getProfileConfig($profileName);

                // Respect profile max_tokens if configured
                $profileMaxTokens = isset($profileConfig['max_tokens']) ? (int)$profileConfig['max_tokens'] : $ai_token;
                if ($profileMaxTokens > 0) {
                    $ai_token = min($ai_token, $profileMaxTokens);
                }

                $messages = [];

                if (!empty($profileConfig['system_prompt'])) {
                    $messages[] = [
                        'role' => 'system',
                        'content' => $profileConfig['system_prompt'],
                    ];
                }

                // Template-level extra system prompt (if present)
                if (!empty($template->system_prompt)) {
                    $messages[] = [
                        'role' => 'system',
                        'content' => $template->system_prompt,
                    ];
                }

                $messages[] = [
                    'role' => 'user',
                    'content' => $prompt,
                ];

                $model = $profileConfig['model'] ?? config('titandocs.titan_core.default_model', 'gpt-5-mini');
                [$text, $tokens] = $this->generateText($messages, $model, $ai_token, $prompt);
                
                if ($text !== '')
                {
                    $flag = AiTemplateLanguage::where('code', $request->language)->first();


                    $data['text'] = trim($text);

                    $prompt_store=AiPromptHistory::where("id",$request->document_id)->where('created_by',auth()->user()->id)->first();

                    $prompt_store->template_id = $template->id;
                    $prompt_store->creativity = $ai_creativity;
                    $prompt_store->max_tokens = $ai_token;
                    $prompt_store->max_results = $max_results;
                    $prompt_store->model = $model;
                    $prompt_store->prompt = $prompt;
                    $prompt_store->language = $post["language"];
                    $prompt_store->prompt_fields = json_encode($data_field);
                    // Titan Core metadata
                    $prompt_store->titan_profile = $profileName;
                    $prompt_store->titan_model = $profileConfig['model'] ?? config('titandocs.titan_core.default_model', 'gpt-5-mini');
                    $prompt_store->template_version = $template->version ?? $prompt_store->template_version;
                    // Link back to project/client if they existed on initial placeholder
                    if ($request->has('project_id')) {
                        $prompt_store->project_id = $request->project_id;
                    }
                    if ($request->has('client_id')) {
                        $prompt_store->client_id = $request->client_id;
                    }
                    $prompt_store->created_by = auth()->user()->id;
                    $prompt_store->save();

                    $content = new AiPromptResponse();
                    $content->template_id = $template->id;
                    $content->history_prompt_id = $prompt_store->id;
                    $content->used_words=$tokens;
                    $content->content = $data['text'];
                    $content->titan_profile = $profileName;
                    $content->titan_model = $profileConfig['model'] ?? config('titandocs.titan_core.default_model', 'gpt-5-mini');
                    $content->template_version = $template->version ?? null;
                    if ($request->has('project_id')) {
                        $content->project_id = $request->project_id;
                    }
                    if ($request->has('client_id')) {
                        $content->client_id = $request->client_id;
                    }
                    $content->created_by = auth()->user()->id;
                    $content->save();

                    $data['prompt'] = $prompt;
                $data['status'] = 'success';
                $data['id'] = $content->id;
                return $data;

                }
                else
                {
                    $data['status'] = 'error';
                    $data['message'] = __('Text was not generated, please try again');
                    return $data;
                }
            }
            else
            {
                $data['status'] = 'error';
                $data['message'] =__('Permission Denied.');
                return $data;
            }
        }
    }
    public function regenerate_response(Request $request)
    {
        if ($request->ajax())
        {
            if ($this->userCan('ai document generate'))
            {
                $data=array();
                $prompt = '';
                $model = '';
                $text = '';
                $ai_token = '';
                $counter = 1;
                $prompt=AiPromptHistory::where('id',$request->prompt_id)->where('created_by',auth()->user()->id)->first();
                $ai_token = (int)$prompt->max_tokens;

                $max_results = (int)$prompt->max_results;
                $ai_creativity = (float)$prompt->creativity;
                $model=$prompt->model;

                // Determine Titan Core profile based on original template category
                $template = AiTemplate::find($prompt->template_id);
                $profileName = $this->getProfileNameByCategory(optional($template)->category_id ?? null);
                $profileConfig = $this->getProfileConfig($profileName);

                // Respect profile max_tokens if configured
                $profileMaxTokens = isset($profileConfig['max_tokens']) ? (int)$profileConfig['max_tokens'] : (int)$prompt->max_tokens;
                $max_tokens = (int)$prompt->max_tokens;
                if ($profileMaxTokens > 0) {
                    $max_tokens = min($max_tokens, $profileMaxTokens);
                }

                $messages = [];

                if (!empty($profileConfig['system_prompt'])) {
                    $messages[] = [
                        'role' => 'system',
                        'content' => $profileConfig['system_prompt'],
                    ];
                }

                if (!empty(optional($template)->system_prompt)) {
                    $messages[] = [
                        'role' => 'system',
                        'content' => $template->system_prompt,
                    ];
                }

                $messages[] = [
                    'role' => 'user',
                    'content' => $prompt->prompt,
                ];

                $model = $profileConfig['model'] ?? config('titandocs.titan_core.default_model', 'gpt-5-mini');
                [$text, $tokens] = $this->generateText($messages, $model, $max_tokens, (string) $prompt->prompt);
                if ($text !== '')
                {
                    $flag = AiTemplateLanguage::where('code', $request->language)->first();


                    $data['text'] = trim($text);


                    $content = new AiPromptResponse();
                    $content->template_id = $request->template_id;
                    $content->history_prompt_id = $request->prompt_id;
                    $content->used_words=$tokens;
                    $content->content = $data['text'];
                    $content->created_by = auth()->user()->id;
                    $content->save();

                    $data['prompt'] = $prompt->prompt;
                    $data['status'] = 'success';
                    $data['id'] = $content->id;
                    return $data;

                }
                else
                {
                    $data['status'] = 'error';
                    $data['message'] = __('Text was not generated, please try again');
                    return $data;
                }
            }
            else
            {
                $data['status'] = 'error';
                $data['message'] =__('Permission Denied.');
                return $data;
            }
        }
    }
    public function exportallresponsecontent(Request $request)
    {
        $template=AiPromptResponse::select('content')->where('history_prompt_id',$request->history_prompt_id)->where('created_by',Auth::user()->id)->get();
        $html="";
        foreach ($template as  $value) {
            $html.=$value->content."<br><br><br>";
        }
        return $html;
    }
    public function exportresponsecontent(Request $request)
    {
        $template=AiPromptResponse::select('content')->where('id',$request->response_id)->where('created_by',Auth::user()->id)->get();
        $html="";
         foreach ($template as  $value) {
            $html.=$value->content;
        }
        return $html;
    }
    public function edit($doc_id,$id)
    {
        if ($this->userCan('document history edit'))
        {
            $languages = AiTemplateLanguage::where('status',1)->orderBy('id', 'asc')->get();

            $template = AiTemplate::where('id', $id)->first();
            $html="";
            if($template)
            {
                $document=AiPromptHistory::where('id',$doc_id)->first();

                $history_data=AiPromptResponse::where("created_by",auth()->user()->id)->where("history_prompt_id",$doc_id)->orderBy('id', 'DESC')->get();
                if(!empty($document->prompt_fields))
                {
                    $field_data=json_decode($document->prompt_fields);
                    foreach ($field_data->field as  $value) {
                       $html.='<div class="form-group col-md-12">
                                    <label class="form-label ">'.$value->label.'</label>';
                                    if($value->field_type=="text_box")
                                    {
                                        $html.='<input type="text" class="form-control" name="'.$value->field_name.'" value="'.$value->value.'" placeholder="'.$value->placeholder.'">';
                                    }
                                    if($value->field_type=="textarea")
                                    {
                                        $html.='<textarea type="text" rows=5 class="form-control " id="description" name="'.$value->field_name.'" placeholder="'.$value->placeholder.'" required>'.$value->value.'</textarea>';
                                    }
                        $html.='</div>';
                    }
                }
                else
                {
                    $field_data=json_decode($template->form_fields);
                    foreach ($field_data->field as  $value) {
                       $html.='<div class="form-group col-md-12">
                        <label class="form-label ">'.$value->label.'</label>';
                        if($value->field_type=="text_box")
                        {

                            $html.='<input type="text" class="form-control" name="'.$value->field_name.'" placeholder="'.$value->placeholder.'">';
                        }
                        if($value->field_type=="textarea")
                        {
                            $html.='<textarea type="text" rows=5 class="form-control " id="description" name="'.$value->field_name.'" placeholder="'.$value->placeholder.'" required></textarea>';
                        }
                        $html.='</div>';
                    }
                }
                $tones=$this->tone_list();

                return view('titandocs::document.edit', compact('languages', 'template','html','history_data','tones','document'));
            }
            else
            {
                return redirect()->back()->with('error', __('Document does not exist'));
            }
         }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function save(Request $request)
    {
        $store=AiPromptHistory::where('id',$request->doc_id)->where('created_by',Auth::user()->id)->where('workspace',$this->currentWorkspaceId())->first();
        if($store)
        {
            $store->doc_name=$request->document_name;
            $store->save();

            event(new UpdateHistory($request));

            return 1;
        }
        else
        {
          return 0;
        }

    }
    public function destroy($id)
    {
       
        if ($this->userCan('document history delete'))
        {
            $data=AiPromptHistory::where('id',$id)->first();
            if($data)
            {
                $response=AiPromptResponse::where('history_prompt_id',$id)->delete();

                $data->delete();
                return redirect()->route('titan.docs.history')->with('success', __('Document successfully deleted .'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function history(Request $request)
    {
        if ($this->userCan('document history manage'))
        {


            $document = $this->prepareHistoryCollection(
                AiPromptHistory::where('workspace', $this->currentWorkspaceId())
                    ->where('created_by', auth()->user()->id)
                    ->orderBy('id', 'desc')
                    ->get()
            );

            return view('titandocs::document.history', compact('document'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }



/**
 * History (Documents)
 */
public function historyDocs()
{
    return $this->historyWithKind('doc');
}

/**
 * History (SWMS)
 */
public function historySwms()
{
    return $this->historyWithKind('swms');
}

private function historyWithKind(string $kind)
{
    $document = $this->prepareHistoryCollection(AiPromptHistory::query()
        ->where('created_by', auth()->id())
        ->orderByDesc('id')
        ->get()
        ->filter(function (AiPromptHistory $history) use ($kind): bool {
            $meta = $history->titan_meta;

            if (is_string($meta) && $meta !== '') {
                $meta = json_decode($meta, true);
            }

            $docKind = is_array($meta) ? ($meta['doc_kind'] ?? 'doc') : 'doc';

            return $kind === 'swms' ? $docKind === 'swms' : $docKind !== 'swms';
        })
        ->values());

    return view('titandocs::document.history', $this->data + compact('document', 'kind') + [
        'historyKind' => $kind,
    ]);
}

    protected function userCan(string $permission): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if (method_exists($user, 'isAbleTo')) {
            return (bool) $user->isAbleTo($permission);
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return true;
        }

        return method_exists($user, 'can') ? (bool) $user->can($permission) : false;
    }

    protected function currentWorkspaceId(): ?int
    {
        return auth()->user()?->organization_id;
    }

    protected function prepareHistoryCollection($document)
    {
        foreach ($document as $value) {
            $value->category = '';
            $value->category_id = '';
            $template = AiTemplate::where('id', $value->template_id)->first();

            if ($template) {
                $category = AiTemplateCategory::where('id', $template->category_id)->first();
                if ($category) {
                    $value->category_id = $category->id;
                    $value->category = $category->name;
                }
            }

            $value->created_on = date('M j, Y H:i', strtotime($value->created_at));

            if ($value->language == '' && empty($value->language)) {
                $value->language = 'en-US';
            }

            if ($value->max_tokens == '' && empty($value->max_tokens)) {
                $value->max_tokens = 0;
            }
        }

        return $document;
    }

    /**
     * @param  array<int, array<string, mixed>>  $messages
     * @return array{0: string, 1: int}
     */
    protected function generateText(array $messages, string $model, int $maxTokens, string $fallbackPrompt): array
    {
        $content = '';
        $tokens = 0;

        if (app()->bound(TitanClient::class)) {
            $response = app(TitanClient::class)->chat($messages, [
                'model' => $model,
                'max_tokens' => $maxTokens,
            ]);

            if (($response['ok'] ?? false) && is_string($response['content'] ?? null)) {
                $content = trim((string) $response['content']);
                $tokens = (int) data_get($response, 'usage.completion_tokens', 0);
            } elseif (isset($response['choices']) && is_array($response['choices'])) {
                $choices = $response['choices'];
                $firstChoice = $choices[0] ?? [];
                $content = trim((string) ($firstChoice['text'] ?? data_get($firstChoice, 'message.content', '')));
                $tokens = (int) data_get($response, 'usage.completion_tokens', 0);
            }
        }

        if ($content === '') {
            $content = trim($fallbackPrompt);
            $tokens = str_word_count($content);
        }

        return [$content, $tokens];
    }
}
