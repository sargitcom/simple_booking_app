import React, { ChangeEvent, useState } from 'react';
import axios from 'axios';
import {url} from '../../config/BackendServer';

const AddEvent : React.FC = () => {   
    const [eventName, setEventName] = useState<string>('');
    const [isSaving, setIsSaving] = useState<boolean>(false);
    const [isError, setIsError] = useState<boolean>();

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
    }

    const addEventHandler = () => {
        setIsSaving(true);

        const res = axios({
            method: 'post',
            url: url.addEvent,
            params:{
                'eventName' : eventName
            }
        }).then((response) => setResponseHandler(response)).catch((error) => setErrorInfoHandler());
    }

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