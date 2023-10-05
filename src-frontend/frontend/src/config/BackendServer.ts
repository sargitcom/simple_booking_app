const BackendServer = {
    host : 'https://app-backend-php-apache:9080/',
}

const Urls = {
    addEvent : BackendServer.host + '/',
    
    addEventAvailableSeats : BackendServer.host + '/',

    reserveEventSeats : BackendServer.host + '/',

    getEvents : BackendServer.host + '/',
}

export const url = Urls;

export default BackendServer;