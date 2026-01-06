<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookingRequest;
use App\Http\Requests\Admin\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\User;
use App\Models\Ruangan;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(private BookingService $service)
    {
        $this->middleware(['auth','verified','active','role:admin|takmir']);
        $this->authorizeResource(Booking::class, 'booking');
    }

    public function index(Request $request)
    {
        $q = $request->input('q');
        $st= $request->input('status');
        $rid= $request->input('ruangan_id');
        $uid= $request->input('user_id');
        $df= $request->input('date_from');
        $dt= $request->input('date_to');
        $sort=$request->input('sort','created_at');
        $dir=$request->input('dir','desc');

        $items = Booking::query()->with(['user','ruangan'])
            ->when($q, function($qq) use ($q){
                $qq->whereHas('user', fn($u)=>$u->where('username','like',"%$q%"))
                   ->orWhereHas('ruangan', fn($r)=>$r->where('nama_ruangan','like',"%$q%"));
            })
            ->when($st, fn($qq)=>$qq->where('status',$st))
            ->when($rid, fn($qq)=>$qq->where('ruangan_id',$rid))
            ->when($uid, fn($qq)=>$qq->where('user_id',$uid))
            ->when($df, fn($qq)=>$qq->where('hari_tanggal','>=',\Carbon\Carbon::parse($df,'Asia/Jakarta')->startOfDay()->utc()))
            ->when($dt, fn($qq)=>$qq->where('hari_tanggal','<=',\Carbon\Carbon::parse($dt,'Asia/Jakarta')->endOfDay()->utc()))
            ->orderBy($sort,$dir)
            ->paginate(12)
            ->withQueryString();

        $users = User::orderBy('username')->get(['user_id','username']);
        $ruangans = Ruangan::orderBy('nama_ruangan')->get(['ruangan_id','nama_ruangan']);

        return view('admin.booking.index', compact('items','q','st','rid','uid','df','dt','sort','dir','users','ruangans'));
    }

    public function create()
    {
        $users = User::orderBy('username')->get(['user_id','username']);
        $ruangans = Ruangan::orderBy('nama_ruangan')->get(['ruangan_id','nama_ruangan']);
        return view('admin.booking.create', compact('users','ruangans'));
    }

    public function store(StoreBookingRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.booking.index')->with('status','Booking hold dibuat.');
    }

    public function show(Booking $booking)
    {
        return view('admin.booking.show', ['b'=>$booking->load(['user','ruangan'])]);
    }

    public function edit(Booking $booking)
    {
        $users = User::orderBy('username')->get(['user_id','username']);
        $ruangans = Ruangan::orderBy('nama_ruangan')->get(['ruangan_id','nama_ruangan']);
        return view('admin.booking.edit', compact('booking','users','ruangans'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $this->service->update($booking, $request->validated());
        return redirect()->route('admin.booking.index')->with('status','Booking diperbarui.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('status','Booking dihapus.');
    }
}

