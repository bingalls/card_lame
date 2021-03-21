<template>
    <div>
        <!--suppress HtmlUnknownTarget -->
        <form action="/dashboard" method="post">    <!-- change to ajax someday -->
            <input type="hidden" name="_token" v-bind:value="csrf_token">
            <label>Enter card chars:
                <input
                    name="cards"
                    pattern="^([jqkaJQKA2-9]|(10))( ([jqkaJQKA2-9]|(10))){0,12}$"
                    required="required"
                    title="requires up to 13 space delimited chars"
                    type="text"
                />
            </label>
            <input type="submit" value="Play">
        </form>

        <table v-if="plays.hasOwnProperty(0)" class="col-md-12">
            <tr>
                <th>Player</th>
                <th>Opponent</th>
                <th>Won</th>
            </tr>
            <tr v-for="play in plays">
                <td>{{play["player"]}}</td>
                <td>{{play["opponent"]}}</td>
                <td>{{showWin(play['win'])}}</td>
            </tr>
        </table>

        <br />
        <div v-if="plays.hasOwnProperty(0)" class="container align-self-center">
            <div class="row justify-content-evenly">
                <div class="col">Player: {{scores['player']}}</div>
                <div class="col">Opponent: {{scores['opponent']}}</div>
            </div>
            <div class="row justify-content-evenly">
                <h2 class="col-lg-auto">Winner is {{showTotal(scores['winner'])}}</h2>
            </div>
        </div>
    </div>
</template>

<script>
// import axios from 'axios'
// let csrf_token = document.head.querySelector('meta[name="csrf-token"]');
// axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// if (csrf_token) { axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf_token.content; }

export default {
    computed: {
        csrf_token: function() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        },
    },
    props: {
        plays: {
            type: Array,
            required: true,
        },
        scores: {
            type: Object,
        }
    },
    methods: {
        showWin: function(val) {
            switch (val) {
                case 'w': return '\u{2713}';     // tick check mark
                case 'l': return 'x';
            }
            return '-';     // tie
        },
        showTotal: function(val) {
            switch(val) {
                case 'l': return 'Opponent';
                case 'w': return 'Player';
            }
            return 'Tie';
        }
    }
}
</script>
