<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bendahara\{StorePaymentRequest, UpdatePaymentRequest};
use App\Models\{Payment, Pemesanan};
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $svc)
    {
        $this->middleware(['auth','verified','active','role:admin|bendahara']);
        $this->authorizeResource(Payment::class, 'payment');
    }

    public function index(Request $request)
    {
        $q=$request->q; $st=$request->status; $gw=$request->gateway; $df=$request->date_from; $dt=$request->date_to; $sort=$request->input('sort','created_at'); $dir=$request->input('dir','desc');
        $items = Payment::query()->with('pemesanan')
            ->when($q, fn($qq)=>$qq->whereHas('pemesanan', fn($w)=>$w->where('tujuan_pemesanan','like',"%$q%")))
            ->when($st, fn($qq)=>$qq->where('status',$st))
            ->when($gw, fn($qq)=>$qq->where('gateway',$gw))
            ->when($df, fn($qq)=>$qq->where('created_at','>=',\Carbon\Carbon::parse($df,'Asia/Jakarta')->startOfDay()->utc()))
            ->when($dt, fn($qq)=>$qq->where('created_at','<=',\Carbon\Carbon::parse($dt,'Asia/Jakarta')->endOfDay()->utc()))
            ->orderBy($sort,$dir)->paginate(12)->withQueryString();
        return view('bendahara.payment.index', compact('items','q','st','gw','df','dt','sort','dir'));
    }

    public function create()
    { $pemesanan = Pemesanan::orderBy('created_at','desc')->get(['pemesanan_id','tujuan_pemesanan']); return view('bendahara.payment.create', compact('pemesanan')); }

    public function store(StorePaymentRequest $request)
    { $p = $this->svc->create($request->validated()); return redirect()->route('bendahara.payment.show',$p)->with('status','Payment dibuat.'); }

    public function show(Payment $payment)
    { return view('bendahara.payment.show', ['p'=>$payment->load('pemesanan')]); }

    public function edit(Payment $payment)
    { return view('bendahara.payment.edit', ['payment'=>$payment, 'pemesanan'=>Pemesanan::orderBy('created_at','desc')->get(['pemesanan_id','tujuan_pemesanan'])]); }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    { $payment->update($request->validated()); return back()->with('status','Payment diperbarui.'); }

    public function destroy(Payment $payment)
    { $payment->delete(); return back()->with('status','Payment dihapus.'); }

    public function markPaid(Payment $payment)
    { $this->authorize('markPaid',$payment); $this->svc->markPaid($payment); return back()->with('status','Ditandai LUNAS.'); }

    public function callback(Request $request, string $gateway, string $externalRef)
    {
        $status = $request->input('status','paid');
        $payload = $request->except(['status']);
        $payment = $this->svc->processCallback($gateway, $externalRef, $status, $payload);
        if (!$payment) return response()->json(['message'=>'not found'], 404);
        return response()->json(['message'=>'ok','payment_id'=>$payment->payment_id,'status'=>$payment->status->value]);
    }
}

