<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Friendship;
use App\Models\User;

class FriendController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Accepted friends
        $friends = User::whereIn('id', function ($q) use ($user) {
                $q->select('requester_id')->from('friendships')
                  ->where('addressee_id', $user->id)->where('status', 'accepted');
            })
            ->orWhereIn('id', function ($q) use ($user) {
                $q->select('addressee_id')->from('friendships')
                  ->where('requester_id', $user->id)->where('status', 'accepted');
            })
            ->withCount('quizzes')
            ->with(['quizAttempts' => function ($q) {
                $q->with('quiz:id,title')
                  ->whereNotNull('finished_at')
                  ->latest('finished_at')
                  ->limit(5);
            }])
            ->get();

        // Pending received
        $pendingReceived = Friendship::where('addressee_id', $user->id)
            ->where('status', 'pending')
            ->with('requester')
            ->latest()
            ->get();

        // Pending sent
        $pendingSent = Friendship::where('requester_id', $user->id)
            ->where('status', 'pending')
            ->with('addressee')
            ->latest()
            ->get();

        // Search
        $searchResults = collect();
        if ($request->filled('search')) {
            $friendIds = $friends->pluck('id')->push($user->id);
            $pendingSentIds = $pendingSent->pluck('addressee_id');

            $searchResults = User::where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->whereNotIn('id', $friendIds)
                ->whereNull('deleted_at')
                ->take(10)
                ->get()
                ->map(function ($u) use ($pendingSentIds) {
                    $u->request_sent = $pendingSentIds->contains($u->id);
                    return $u;
                });
        }

        return view('friends.index', compact(
            'friends', 'pendingReceived', 'pendingSent', 'searchResults'
        ));
    }

    public function sendRequest(User $user)
    {
        $me = Auth::user();

        if (!$me->canAddFriend()) {
            return back()->with('error', 'Masz już maksymalną liczbę 10 znajomych.');
        }

        if (!$user->canAddFriend()) {
            return back()->with('error', 'Użytkownik ma już maksymalną liczbę 10 znajomych.');
        }

        // Check not already friends or pending
        $exists = Friendship::where(function ($q) use ($me, $user) {
            $q->where('requester_id', $me->id)->where('addressee_id', $user->id);
        })->orWhere(function ($q) use ($me, $user) {
            $q->where('requester_id', $user->id)->where('addressee_id', $me->id);
        })->exists();

        if (!$exists) {
            Friendship::create([
                'requester_id' => $me->id,
                'addressee_id' => $user->id,
                'status'       => 'pending',
            ]);
        }

        return back()->with('success', 'Zaproszenie wysłane!');
    }

    public function accept(Friendship $friendship)
    {
        abort_if($friendship->addressee_id !== Auth::id(), 403);

        $requester = User::find($friendship->requester_id);
        $addressee = Auth::user();

        if (!$requester || !$requester->canAddFriend()) {
            return back()->with('error', 'Użytkownik nie może mieć więcej niż 10 znajomych.');
        }

        if (!$addressee->canAddFriend()) {
            return back()->with('error', 'Masz już maksymalną liczbę 10 znajomych.');
        }

        $friendship->update([
            'status'      => 'accepted',
            'accepted_at' => now(),
        ]);

        return back()->with('success', 'Zaakceptowano zaproszenie!');
    }

    public function reject(Friendship $friendship)
    {
        abort_if($friendship->addressee_id !== Auth::id(), 403);

        $friendship->update(['status' => 'rejected']);

        return back()->with('success', 'Odrzucono zaproszenie.');
    }

    public function remove(Friendship $friendship)
    {
        abort_if(
            $friendship->requester_id !== Auth::id() &&
            $friendship->addressee_id !== Auth::id(),
            403
        );

        $friendship->delete();

        return back()->with('success', 'Usunięto znajomego.');
    }
}
