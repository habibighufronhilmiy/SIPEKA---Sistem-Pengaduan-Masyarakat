<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PilihanVoting;
use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VotingController extends Controller
{
    public function index()
    {
        $votings = Voting::with('pilihans.users')
            ->withCount('pilihans')
            ->latest()
            ->paginate(10);

        return view('voting.index', compact('votings'));
    }

    public function create()
    {
        return view('voting.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'pilihans' => 'required|array|min:2',
            'pilihans.*' => 'required|string|max:255',
        ]);

        $voting = Voting::create([
            'id_user' => Auth::id(),
            'pertanyaan' => $validated['pertanyaan'],
            'deskripsi' => $validated['deskripsi'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'status' => 'aktif',
        ]);

        foreach ($validated['pilihans'] as $pilihan) {
            PilihanVoting::create([
                'id_voting' => $voting->id,
                'pilihan' => $pilihan,
            ]);
        }

        AuditLog::log('Membuat voting', 'Membuat voting: ' . $voting->pertanyaan, $voting, 'voting');

        return redirect()->route('voting.index')->with('success', 'Voting berhasil dibuat.');
    }

    public function show(Voting $voting)
    {
        $voting->load(['pilihans.users', 'user']);
        return view('voting.show', compact('voting'));
    }

    public function vote(Request $request, Voting $voting)
    {
        $request->validate([
            'id_pilihan' => 'required|exists:pilihan_votings,id',
        ]);

        if ($voting->status !== 'aktif') {
            return back()->with('error', 'Voting sudah ditutup.');
        }

        $sudahVote = \App\Models\VotingUser::where('id_voting', $voting->id)
            ->where('id_user', Auth::id())
            ->exists();

        if ($sudahVote) {
            return back()->with('error', 'Anda sudah memberikan suara.');
        }

        \App\Models\VotingUser::create([
            'id_voting' => $voting->id,
            'id_pilihan' => $request->id_pilihan,
            'id_user' => Auth::id(),
        ]);

        return back()->with('success', 'Suara berhasil diberikan.');
    }

    public function edit(Voting $voting)
    {
        $voting->load('pilihans');
        return view('voting.edit', compact('voting'));
    }

    public function update(Request $request, Voting $voting)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:aktif,ditutup',
        ]);

        $voting->update($validated);

        AuditLog::log('Mengupdate voting', 'Mengupdate voting: ' . $voting->pertanyaan, $voting, 'voting');

        return redirect()->route('voting.index')->with('success', 'Voting berhasil diperbarui.');
    }

    public function destroy(Voting $voting)
    {
        AuditLog::log('Menghapus voting', 'Menghapus voting: ' . $voting->pertanyaan, null, 'voting');
        $voting->delete();
        return redirect()->route('voting.index')->with('success', 'Voting berhasil dihapus.');
    }
}
