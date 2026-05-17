<?php

namespace Modules\Security\Http\Controllers;

use Carbon\Carbon;
use App\Helper\Files;
use App\Helper\Reply;
use App\Models\BaseModel;
use Illuminate\Http\Request;
use Modules\Units\Entities\Unit;
use Illuminate\Support\Facades\DB;
use Modules\Security\Entities\WorkPermits;
use App\Http\Controllers\AccountBaseController;
use Modules\Security\DataTables\WorkPermitDataTable;
use Modules\Security\Http\Requests\StoreWorkPermits;

class WorkPermitsController extends AccountBaseController
{
    public $arr = [];

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'trworkpermits::modules.workPermit';
        $this->pageIcon  = 'ti-settings';
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(WorkPermitDataTable $dataTable)
    {
        $viewPermission = user()->permission('view_work_permits');
        abort_403(!in_array($viewPermission, ['all','owned']));

        $this->tenan = WorkPermits::all();
        return $dataTable->render('trworkpermits::work-permit.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $viewPermission = user()->permission('add_work_permits');
        abort_403(!in_array($viewPermission, ['all','owned']));

        $this->pageTitle = __('trworkpermits::app.workpermits.add');
        $this->units     = Unit::all();
        $this->notes     = DB::table('notes')
            ->where('table_name', 'workpermits')
            ->get();

        if (request()->ajax()) {
            $html = view('trworkpermits::work-permit.ajax.create', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'trworkpermits::work-permit.ajax.create';

        return view('trworkpermits::work-permit.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreWorkPermits $request)
    {
        $WP = new WorkPermits();
        $WP->date_start        = Carbon::createFromFormat('d-m-Y', $request->date_start)->format('Y-m-d');
        $WP->date_end          = Carbon::createFromFormat('d-m-Y', $request->date_end)->format('Y-m-d');
        $WP->date              = $request->input('date');
        $WP->company_name      = $request->input('company_name');
        $WP->company_address   = $request->input('company_address');
        $WP->project_manj      = $request->input('project_manj');
        $WP->site_coor         = $request->input('site_coor');
        $WP->phone             = $request->input('phone');
        $WP->jenis_pekerjaan   = $request->input('jenis_pekerjaan');
        $WP->lingkup_pekerjaan = $request->input('lingkup_pekerjaan');
        $WP->unit_id           = $request->input('unit_id');
        $WP->detail_pekerjaan  = $request->input('detail_pekerjaan');
        $WP->created_by        = user()->id;
        $WP->save();

        $redirectUrl = urldecode($request->redirect_url);
        if ($redirectUrl == '') {
            $redirectUrl = route('work-permits.index');
        }
        return Reply::successWithData(__('trworkpermits::messages.workpermit.add'), ['wp_id' => $WP->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $viewPermission = user()->permission('view_work_permits');
        abort_403(!in_array($viewPermission, ['all','owned']));

        $this->pageTitle = __('trworkpermits::app.workpermits.show');
        $this->wp        = WorkPermits::with('unit', 'approved', 'validated', 'files')->findOrFail($id);
        $this->url       = asset_url('validasi-tenancy/' . $this->wp->validated_img);
        $this->notes     = DB::table('notes')
            ->where('table_name', 'workpermits')
            ->get();

        if (request()->ajax()) {
            $html = view('trworkpermits::work-permit.ajax.show', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'trworkpermits::work-permit.ajax.show';
        return view('trworkpermits::work-permit.create', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $viewPermission = user()->permission('edit_work_permits');
        abort_403(!in_array($viewPermission, ['all','owned']));

        $this->pageTitle = __('trworkpermits::app.workpermits.edit');
        $this->wp        = WorkPermits::findOrFail($id);
        $this->units     = Unit::all();


        if (request()->ajax()) {
            $html = view('trworkpermits::work-permit.ajax.edit', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'trworkpermits::work-permit.ajax.edit';
        return view('trworkpermits::work-permit.create', $this->data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(StoreWorkPermits $request, $id)
    {
        $WP                    = WorkPermits::find($id);
        $WP->date_start        = Carbon::createFromFormat('d-m-Y', $request->date_start)->format('Y-m-d');
        $WP->date_end          = Carbon::createFromFormat('d-m-Y', $request->date_end)->format('Y-m-d');
        $WP->company_name      = $request->input('company_name');
        $WP->company_address   = $request->input('company_address');
        $WP->project_manj      = $request->input('project_manj');
        $WP->site_coor         = $request->input('site_coor');
        $WP->phone             = $request->input('phone');
        $WP->jenis_pekerjaan   = $request->input('jenis_pekerjaan');
        $WP->lingkup_pekerjaan = $request->input('lingkup_pekerjaan');
        $WP->unit_id           = $request->input('unit_id');
        $WP->detail_pekerjaan  = $request->input('detail_pekerjaan');
        $WP->save();

        $redirectUrl = urldecode($request->redirect_url);
        if ($redirectUrl == '') {
            $redirectUrl = route('work-permits.index');
        }
        return Reply::successWithData(__('trworkpermits::messages.workpermit.edit'), ['redirectUrl' => $redirectUrl]);
    }

    public function applyQuickAction(Request $request)
    {
        switch ($request->action_type) {
            case 'delete':
                $this->deleteRecords($request);
                return Reply::success(__('trworkpermits::messages.deleteTransfer'));
            default:
                return Reply::error(__('trworkpermits::messages.selectAction'));
        }
    }

    protected function deleteRecords($request)
    {
        WorkPermits::whereIn('id', explode(',', $request->row_ids))->forceDelete();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $viewPermission = user()->permission('delete_work_permits');
        abort_403(!in_array($viewPermission, ['all','owned']));

        WorkPermits::destroy($id);

        $redirectUrl = route('work-permits.index');
        return Reply::successWithData(__('trworkpermits::messages.workpermit.delete'), ['redirectUrl' => $redirectUrl]);
    }

    public function download($id)
    {
        $this->tenancy     = WorkPermits::with('unit')->findOrFail($id);
        $this->unit_detail = Unit::with('floor', 'tower')->findOrFail($this->tenancy->unit_id);
        $pdfOption         = $this->domPdfObjectForDownload($id);
        $pdf               = $pdfOption['pdf'];
        $filename          = $pdfOption['fileName'];

        return request()->view ? $pdf->stream($filename . '.pdf') : $pdf->download($filename . '.pdf');
    }

    public function domPdfObjectForDownload($id)
    {
        $this->tenancy     = WorkPermits::with('unit')->findOrFail($id);
        $this->unit_detail = Unit::findOrFail($this->tenancy->unit_id);
        $pdf               = app('dompdf.wrapper');
        $pdf->setOption('enable_php', false);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        $pdf->loadView('trworkpermits::work-permit.pdf.invoice', $this->data);
        $filename = 'permission-' . $this->tenancy->company_name;

        return [
            'pdf'      => $pdf,
            'fileName' => $filename
        ];
    }

    public function approved($id)
    {
        $mr                  = WorkPermits::findOrFail($id);
        $mr->status_approve  = true;
        $mr->approved_by     = user()->id;
        $mr->approved_at = Carbon::now();
        $mr->save();

        $redirectUrl = route('work-permits.index');
        return redirect($redirectUrl)->with('success', __('trworkpermits::messages.updateTransfer'));
    }

    public function approved_bm($id)
    {
        $mr                     = WorkPermits::findOrFail($id);
        $mr->status_approve_bm  = true;
        $mr->approved_bm        = user()->id;
        $mr->approved_bm_at = Carbon::now();
        $mr->save();

        $redirectUrl = route('work-permits.index');
        return redirect($redirectUrl)->with('success', __('trworkpermits::messages.updateTransfer'));
    }

    public function validateData($id)
    {
        $viewPermission = user()->permission('edit_work_permits');
        abort_403(!in_array($viewPermission, ['all','owned']));

        $this->pageTitle = __('trworkpermits::app.workpermits.validateTransfer');
        $this->tenancy   = WorkPermits::findOrFail($id);
        $this->units     = Unit::all();


        if (request()->ajax()) {
            $html = view('trworkpermits::work-permit.ajax.validate', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'trworkpermits::work-permit.ajax.validate';
        return view('trworkpermits::work-permit.create', $this->data);
    }

    public function processValidatedData(Request $request, $id)
    {
        $editUnit = user()->permission('edit_work_permits');
        abort_403(!in_array($editUnit, ['all', 'owned']));

        $wp                        = WorkPermits::findOrFail($id);
        $data['validated_by']       = user()->id;
        $data['validated_remark']   = $request->remark;
        $data['validated_at']       = Carbon::now();
        $data['status_validated']   = true;

        if ($request->image_delete == 'yes') {
            Files::deleteFile($wp->validated_img, 'validasi-tenancy');
            $data['validated_img'] = null;
        }

        if ($request->hasFile('image')) {
            Files::deleteFile($wp->validated_img, 'validasi-tenancy');
            $data['validated_img'] = Files::upload($request->image, 'validasi-tenancy', 300);
        }

        $wp->update($data);

        $redirectUrl = route('work-permits.index');
        return Reply::successWithData(__('trworkpermits::messages.updateTransfer'), ['redirectUrl' => $redirectUrl]);
    }
    public function export()
    {
        return redirect()->route('work-permits.index');
    }

    public function client()
    {
        return redirect()->route('work-permits.index');
    }

}
