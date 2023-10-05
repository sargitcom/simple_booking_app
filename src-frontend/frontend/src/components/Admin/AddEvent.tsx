import React, { ChangeEvent, useState, useEffect } from 'react';
import axios from 'axios';
import {url} from '../../config/BackendServer';

const AddEvent : React.FC = () => {   
    const [eventName, setEventName] = useState<string>('');
    const [isSaving, setIsSaving] = useState<boolean>(false);
    const [isError, setIsError] = useState<boolean>();
    const [refreshEvents, setRefreshEvents] = useState<boolean>(false);

    const updateEventNameHandler = (event : ChangeEvent<HTMLInputElement>) => {
        setEventName(event.target.value);
    }

    const setErrorInfoHandler = () => {
        setIsSaving(false);
        setIsError(true);
    }

    const setResponseHandler = (response : any) => {
        setIsSaving(false);
        setIsError(false);
        setRefreshEvents(true);
    }

    const addEventHandler = () => {
        setIsSaving(true);

        const res = axios.post(url.addEvent, {
            'eventName' : eventName
        }, {
            headers: {
              'Content-Type': 'application/json'
            }
        }).then((response) => setResponseHandler(response)).catch((error) => setErrorInfoHandler());
    }

    useEffect(() => {
        if (!refreshEvents) {
            const interval = setInterval(() => {
                console.log('odswiez');
                clearInterval(interval);
            }, 1000);
            return () => {
                setRefreshEvents(false);
                clearInterval(interval)
            };
        }

      }, [refreshEvents]);

    return <>
        <div className={'add-event'}>
            <input type={"text"} onChange={updateEventNameHandler} value={eventName} />
            <button onClick={addEventHandler}>Add event</button>
            {isSaving && <p>Trwa zapisywanie...</p>}
            {isError && <p>Wystąpił błąd podczas zapisu. Spróbuj ponownie później</p>}
        </div>
    </>
}

export default AddEvent;