<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <template v-if="inMatches">
                    <match-list :matches="matches"
                                :loading="loading"
                                @showMatch="showMatch"
                                @createMatch="createMatch"
                                @removeMatch="removeMatch"/>
                </template>
                <template v-if="inMatch">
                    <match :match="match"
                           :currentPlayer="currentPlayer"
                           :loading="loading"
                           @load="loadMatch(match.id)"
                           @fetchCurrentPlayer="fetchCurrentPlayer(match.id)"
                           @newSession="restartSession()"
                           @move="handleMove"
                           @playAs="fillPlayerSlot"
                           @showMatches="showMatches"/>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import api from '../api';
    import MatchList from "./MatchList";
    import Match from "./Match";

    const
        MATCHES = 'matches',
        MATCH = 'match';

    export default {
        components: {MatchList, Match},
        data: function () {
            return {
                matches: {},
                match: {},
                currentPlayer: null,
                loading: true,
                current: MATCHES,
            }
        },
        computed: {
            inMatches() {
                return this.current === MATCHES;
            },
            inMatch() {
                return this.current === MATCH;
            }
        },
        methods: {
            showMatch(id) {
                this.loading = true;
                // Load match
                api.match({id: id}).then(({data}) => {
                    this.match = data;
                    this.loading = false;
                    this.current = MATCH;
                });
            },
            loadMatch(id) {
                this.loading = true;
                // Load match
                api.match({id: id}).then(({data}) => {
                    this.match = data;
                    this.loading = false;
                });
            },
            fetchCurrentPlayer(id) {
                this.loading = true;
                api.currentPlayer({id: id}).then(( {data} ) => {
                    console.log(data.player);
                    this.currentPlayer = data.player
                    this.loading = false
                })
            },
            restartSession() {
                this.loading = true;
                api.leave().then(() => {
                    this.currentPlayer = null
                    this.loading = false
                })
            },
            showMatches() {
                let that = this;
                that.loading = true;
                this.current = MATCHES;
                // Load matches
                api.matches().then(({data}) => {
                    that.matches = data;
                    that.loading = false;
                });
            },
            handleMove(data) {
                if (!this.currentPlayer) {
                    return alert("Please, wait for next player to connect.");
                }
                let that = this;
                that.loading = true;
                api.move(data).then(({data}) => {
                    this.match = data;
                    that.loading = false;
                });
            },
            fillPlayerSlot(playerData) {
                this.loading = true
                api.registerPlayer(playerData).then(( {data} ) => {
                    this.match = data
                    this.loading = false
                    this.fetchCurrentPlayer(this.match.id);
                })
            },
            createMatch() {
                let that = this;
                that.loading = true;
                api.create().then(({data}) => {
                    that.matches = data;
                    that.loading = false;
                    this.current = MATCHES;
                });
            },
            removeMatch(id) {
                let that = this;
                that.loading = true;
                api.destroy({id: id}).then(({data}) => {
                    that.matches = data;
                    that.loading = false;
                    this.current = MATCHES;
                });
            }
        },
        mounted() {
            this.showMatches();
        }
    }
</script>
