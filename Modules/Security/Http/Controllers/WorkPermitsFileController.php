<?php

namespace Modules\Security\Http\Controllers;

use App\Helper\Files;
use App\Helper\Reply;
use Modules\Security\Entities\WorkPermitsFile;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\Security\Http\Requests\StoreWorkPermitFile;
use Modules\Security\Security\Sanitization\InputSanitizer;
use Modules\Security\Support\Validators\SecureFileUploadValidator;
use InvalidArgumentException;

class WorkPermitsFileController extends AccountBaseController
{
      /**
     * @param Request $request
     * @return mixed|void
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function store(StoreWorkPermitFile $request)
    {

        if ($request->hasFile('file')) {
            $this->storeFiles($request);
            $this->files = WorkPermitsFile::where('wp_id', $request->wp_id)->orderBy('id', 'desc')->get();
            $view        = view('projects.files.show', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'view' => $view]);
        }
    }

    public function storeMultiple(StoreWorkPermitFile $request)
    {
        if ($request->hasFile('file')) {
            $this->storeFiles($request);
        }
    }

    private function storeFiles($request)
    {
        $uploadValidator = app(SecureFileUploadValidator::class);
        $sanitizer = app(InputSanitizer::class);
        $workPermitId = $sanitizer->integer($request->wp_id);
        $files = is_array($request->file) ? $request->file : [$request->file];

        foreach ($files as $fileData) {
            try {
                $uploadValidator->validate($fileData);
            } catch (InvalidArgumentException $exception) {
                abort(422, $exception->getMessage());
            }

            $file           = new WorkPermitsFile();
            $file->wp_id    = $workPermitId;
            $filename       = Files::uploadLocalOrS3($fileData, WorkPermitsFile::FILE_PATH . '/' . $workPermitId);
            $file->user_id  = $this->user->id;
            $file->filename = $sanitizer->filename($fileData->getClientOriginalName());
            $file->hashname = $filename;
            $file->size     = $fileData->getSize();
            $file->save();
        }
    }

    public function destroy(Request $request, $id)
    {
        $file = WorkPermitsFile::findOrFail($id);

        Files::deleteFile($file->hashname, WorkPermitsFile::FILE_PATH . '/' . $file->wp_id);
        WorkPermitsFile::destroy($id);

        $this->files = WorkPermitsFile::where('wp_id', $file->wp_id)->orderBy('id', 'desc')->get();

        $view = view('projects.files.show', $this->data)->render();

        return Reply::successWithData(__('messages.deleteSuccess'), ['view' => $view]);
    }

    public function download($id)
    {
        $file                 = WorkPermitsFile::whereRaw('md5(id) = ?', $id)->firstOrFail();
        $this->viewPermission = user()->permission('view_project_files');
        abort_403(!($this->viewPermission == 'all' || ($this->viewPermission == 'added' && $file->user_id == user()->id)));

        return download_local_s3($file, WorkPermitsFile::FILE_PATH . '/' . $file->wp_id . '/' . $file->hashname);
    }
}
