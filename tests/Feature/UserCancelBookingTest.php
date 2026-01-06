<?php

use App\Models\{User, Ruangan, Booking, AuditLog};
use App\Enums\StatusBooking;
use function Pest\Laravel\actingAs; use function Pest\Laravel\post; use function Pest\Laravel\get;

it('allows user to cancel own booking and writes audit', function(){
    $u=User::factory()->create()->assignRole('user');
    $r=Ruangan::factory()->create(['status'=>'aktif']);
    $b=Booking::factory()->create([
        'user_id'=>$u->user_id,'ruangan_id'=>$r->ruangan_id,
        'hari_tanggal'=>now('Asia/Jakarta')->addDays(1)->utc(),'jam'=>'09:00:00',
        'status'=>StatusBooking::HOLD->value
    ]);

    actingAs($u);
    get(route('user.booking.cancel.confirm',$b))->assertOk();
    post(route('user.booking.cancel',$b), ['reason'=>'Perubahan rencana'])
        ->assertRedirect();

    $b->refresh();
    expect($b->status->value)->toBe('cancelled');
    expect(AuditLog::where('reference_type','Booking')->where('reference_id',$b->booking_id)->exists())->toBeTrue();
});