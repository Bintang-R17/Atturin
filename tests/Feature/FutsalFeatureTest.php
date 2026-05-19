<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CommunityEvent;
use App\Models\Participant;
use App\Models\User;
use Tests\TestCase;

class FutsalFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_match()
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('admin.matches.store'), [
            'nama_event' => 'Sunday Fun Futsal',
            'tanggal' => '2026-04-01',
            'waktu' => '19:00',
            'tempat' => 'Champion Futsal',
            'slot_max' => 10,
            'metode_pembayaran' => 'tunai',
            'iuran_per_pemain' => 50000,
            'show_joined_players_public' => 1,
            'show_joined_player_contacts_public' => 0,
        ]);

        $response->assertRedirect(route('admin.matches.index'));
        $this->assertDatabaseHas('community_events', [
            'nama_event' => 'Sunday Fun Futsal',
            'show_joined_players_public' => 1,
            'show_joined_player_contacts_public' => 0,
        ]);
    }

    public function test_player_can_join_match()
    {
        $this->withoutExceptionHandling();
        $match = CommunityEvent::create([
            'nama_event' => 'Sunday Fun Futsal',
            'tanggal' => '2026-04-01',
            'waktu' => '19:00',
            'tempat' => 'Champion Futsal',
            'slot_max' => 10,
            'metode_pembayaran' => 'tunai',
            'iuran_per_pemain' => 50000,
            'slug' => 'sunday-fun-futsal-123'
        ]);

        $response = $this->post(route('player.join.store', $match->slug), [
            'nama' => 'John Doe',
            'kontak' => '08123456789'
        ]);

        $response->assertRedirect(route('player.join.success', $match->slug));
        
        $this->assertDatabaseHas('participants', [
            'nama' => 'John Doe',
            'kontak' => '08123456789'
        ]);

        $this->assertDatabaseHas('event_participant', [
            'community_event_id' => $match->id,
            'status_join' => 'joined',
            'hadir' => 0,
            'payment_method' => 'tunai',
            'payment_amount' => 50000,
            'payment_status' => 'pending',
        ]);
    }

    public function test_player_cannot_join_if_full()
    {
        $match = CommunityEvent::create([
            'nama_event' => 'Sunday Fun Futsal',
            'tanggal' => '2026-04-01',
            'waktu' => '19:00',
            'tempat' => 'Champion Futsal',
            'slot_max' => 1,
            'metode_pembayaran' => 'tunai',
            'iuran_per_pemain' => 50000,
            'slug' => 'sunday-fun-futsal-123'
        ]);

        // Player 1 joins
        $this->post(route('player.join.store', $match->slug), [
            'nama' => 'John Doe',
            'kontak' => '08123456789'
        ]);

        // Player 2 attempts to join
        $response = $this->post(route('player.join.store', $match->slug), [
            'nama' => 'Jane Doe',
            'kontak' => '08987654321'
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, $match->participants()->count());
    }

    public function test_player_can_simulate_online_banking_payment()
    {
        $match = CommunityEvent::create([
            'nama_event' => 'Online Payment Match',
            'tanggal' => '2026-04-10',
            'waktu' => '20:00',
            'tempat' => 'Arena Simulasi',
            'slot_max' => 5,
            'metode_pembayaran' => 'online_banking',
            'iuran_per_pemain' => 75000,
            'slug' => 'online-payment-match-123'
        ]);

        $this->post(route('player.join.store', $match->slug), [
            'nama' => 'Player Bayar',
            'kontak' => '081200001111'
        ]);

        $response = $this->post(route('player.join.simulatePayment', $match->slug));

        $response->assertSessionHas('success');

        $player = Participant::where('kontak', '081200001111')->firstOrFail();

        $this->assertDatabaseHas('event_participant', [
            'community_event_id' => $match->id,
            'participant_id' => $player->id,
            'payment_method' => 'online_banking',
            'payment_status' => 'paid',
        ]);
    }

    public function test_player_can_see_joined_players_when_public_visibility_enabled()
    {
        $match = CommunityEvent::create([
            'nama_event' => 'Open Member List Match',
            'tanggal' => '2026-05-10',
            'waktu' => '18:00',
            'tempat' => 'Lapangan A',
            'slot_max' => 10,
            'metode_pembayaran' => 'tunai',
            'iuran_per_pemain' => 30000,
            'show_joined_players_public' => true,
            'show_joined_player_contacts_public' => false,
            'slug' => 'open-member-list-match',
        ]);

        $p1 = Participant::create(['nama' => 'Ari', 'kontak' => '081111111111']);
        $p2 = Participant::create(['nama' => 'Beni', 'kontak' => '082222222222']);
        $p3 = Participant::create(['nama' => 'Caca', 'kontak' => '083333333333']);

        $match->participants()->attach($p1->id, ['status_join' => 'joined']);
        $match->participants()->attach($p2->id, ['status_join' => 'joined']);
        $match->participants()->attach($p3->id, ['status_join' => 'batal']);

        $response = $this->get(route('player.join.show', $match->slug));

        $response->assertSee('Roster');
        $response->assertSee('Ari');
        $response->assertSee('Beni');
        $response->assertDontSee('Caca');
        $response->assertDontSee('081111111111');
    }

    public function test_player_cannot_see_joined_players_when_public_visibility_disabled()
    {
        $match = CommunityEvent::create([
            'nama_event' => 'Closed Member List Match',
            'tanggal' => '2026-05-12',
            'waktu' => '19:00',
            'tempat' => 'Lapangan B',
            'slot_max' => 10,
            'metode_pembayaran' => 'tunai',
            'iuran_per_pemain' => 35000,
            'show_joined_players_public' => false,
            'show_joined_player_contacts_public' => false,
            'slug' => 'closed-member-list-match',
        ]);

        $player = Participant::create(['nama' => 'Doni', 'kontak' => '084444444444']);
        $match->participants()->attach($player->id, ['status_join' => 'joined']);

        $response = $this->get(route('player.join.show', $match->slug));

        $response->assertDontSee('Roster');
        $response->assertDontSee('Doni');
    }

    public function test_admin_can_update_public_join_visibility_settings()
    {
        $this->actingAs(User::factory()->create());

        $match = CommunityEvent::create([
            'nama_event' => 'Visibility Settings Match',
            'tanggal' => '2026-05-15',
            'waktu' => '20:00',
            'tempat' => 'Lapangan C',
            'slot_max' => 12,
            'metode_pembayaran' => 'tunai',
            'iuran_per_pemain' => 40000,
            'show_joined_players_public' => true,
            'show_joined_player_contacts_public' => true,
            'slug' => 'visibility-settings-match',
        ]);

        $response = $this->post(route('admin.matches.updateJoinVisibility', $match->id), [
            'show_joined_players_public' => 0,
            'show_joined_player_contacts_public' => 1,
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('community_events', [
            'id' => $match->id,
            'show_joined_players_public' => 0,
            'show_joined_player_contacts_public' => 0,
        ]);
    }
}
