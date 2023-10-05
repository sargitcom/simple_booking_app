const BackendServer = {
    host : 'http://localhost:9080/index.php',
}

const Urls = {
    addEvent : BackendServer.host + '/api/event',
    
    addEventAvailableSeats : BackendServer.host + '/api/available_event_days',

    reserveEventSeats : BackendServer.host + '/api/reserved_event_days/{eventId}',

    getEvents : BackendServer.host + '/api/events/',
}

export const url = Urls;

export default BackendServer;