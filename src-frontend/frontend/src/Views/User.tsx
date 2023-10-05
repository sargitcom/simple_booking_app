import React, { useEffect, useState } from 'react';
import axios from 'axios';
import EventType from '../types/Event';
import EventComponent from '../components/User/Event';

const User : React.FC = () => {

    const [events, setEvents] = useState<EventType[]>([]);

    useEffect(() => {
        
    }, [])

    return <>
        <h1>User</h1>

        {events.map(event => {
            return <EventComponent event={event} />
        })}
    </>
}

export default User;