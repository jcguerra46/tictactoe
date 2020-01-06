import axios from 'axios';

const URL_MATCHES = '/api/match';
const URL_MATCH = '/api/match/';
const URL_MATCH_REGISTER_PLAYER = '/api/match/%s/player';
const URL_MATCH_CURRENT_PLAYER = '/api/match/%s/player/session';
const URL_MOVE = '/api/match/';
const URL_CREATE = '/api/match';
const URL_DELETE = '/api/match/';
const URL_LEAVE_MATCH = '/api/match/all/leave';

export default {
    matches: () => {
        return axios.get(URL_MATCHES)
    },
    match: ({id}) => {
        return axios.get(URL_MATCH + id)
    },
    currentPlayer: ({id}) => {
        return axios.get( URL_MATCH_CURRENT_PLAYER, id )
    },
    registerPlayer: ({player, match_id}) => {
        return axios.put( URL_MATCH_REGISTER_PLAYER, match_id, {
            player: player
        })
    },
    move: ({id, position}) => {
        return axios.put(URL_MOVE + id, {
            position: position
        })
    },
    create: () => {
        return axios.post(URL_CREATE)
    },
    destroy: ({id}) => {
        return axios.delete(URL_DELETE + id)
    },
    leave: () => {
        return axios.put(URL_LEAVE_MATCH)
    },
}
