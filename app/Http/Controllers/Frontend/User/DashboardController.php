<?php

namespace App\Http\Controllers\Frontend\User;

use App\Helpers\ScoresHelper;
use App\Http\Requests\Frontend\User\CardPlayRequest;
use App\Models\Scores;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class DashboardController.
 */
class DashboardController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @noinspection MissingReturnTypeInspection
     * @noinspection PhpFullyQualifiedNameUsageInspection
     * @noinspection PhpUndefinedConstantInspection
     * @throws \JsonException
     */
    final public function index()
    {
        return view(
            'frontend.user.dashboard',
            [
                'plays' => json_encode([], JSON_THROW_ON_ERROR),
                'scores' => '{}',
                'totals' => json_encode(static::leaderboard(), JSON_THROW_ON_ERROR),
                'username' => '',
            ]
        );
    }

    /**
     * Compare User cards against random opponent.
     * Cards are not sorted.
     *
     * @param CardPlayRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @noinspection MissingReturnTypeInspection
     * @noinspection PhpFullyQualifiedNameUsageInspection
     * @noinspection PhpUndefinedConstantInspection
     * @throws \JsonException
     */
    final public function play(CardPlayRequest $request)
    {
        $user = $request->user();
        $userId = $user->id;

        if (! $request->validated()) {      // todo i18n
            return view(
                'frontend.user.dashboard',
                [
                    'plays' => json_encode([], JSON_THROW_ON_ERROR),
                    'scores' => json_encode('{}', JSON_THROW_ON_ERROR),
                    'totals' => json_encode(static::leaderboard(), JSON_THROW_ON_ERROR),
                    'username' => $request->user()->name,
                ]
            );
        }

        $cards = explode(' ', $request->input('cards'));
        $plays = [];
        $oppTotal = 0;
        $playTotal = 0;
        $winner = 't';
        foreach ($cards as $card) {
            $oppScore = random_int(2, 14);     // use old mt_rand() if you lack ext-csprng
            $playScore = ScoresHelper::card2val($card);
            $win = 't';  // tie
            if ($playScore > $oppScore) {
                $win = 'w';
                ++$playTotal;
            } elseif ($playScore < $oppScore) {
                $win = 'l';
                ++$oppTotal;
            }
            $plays[] = [
                'player' => ScoresHelper::val2card($playScore),
                'opponent' => ScoresHelper::val2card($oppScore),
                'win' => $win,
            ];
        }
        if ($oppTotal > $playTotal) {
            $winner = 'l';
        } elseif ($playTotal > $oppTotal) {
            $winner = 'w';
        }

        Scores::insert(
            [
                'users_id' => $userId,
                'user_score' => $playTotal,
                'opponent_score' => $oppTotal,
                'won' => $winner,
            ]
        );

        return view(
            'frontend.user.dashboard',
            [
                'plays' => json_encode($plays, JSON_THROW_ON_ERROR),
                'scores' => json_encode(
                    [
                        'player' => $playTotal,
                        'opponent' => $oppTotal,
                        'winner' => $winner,
                    ],
                    JSON_THROW_ON_ERROR
                ),
                'totals' => json_encode(static::leaderboard(), JSON_THROW_ON_ERROR),
                'username' => $user->name,
            ]
        );
    }

    /**
     * `IIF()` is sqlite. Change to `IF()` for ANSI SQL (e.g. MariaDB)
     *
     * @return Collection
     */
    private static function leaderboard(): Collection
    {
        return DB::table('scores')->select(DB::raw(
            'users.name AS username,
            COUNT(*) AS plays,
            COUNT(IIF(scores.won = "w", 1, NULL)) AS wins'
        ))->join('users', 'users.id', '=', 'scores.users_id')
            ->groupBy('users.name')
            ->get();
    }
}
