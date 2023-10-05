import React, { useState } from 'react';
import EventType from '../../types/Event';
import Seat from './Seat';
import Calendar from 'react-calendar';

const Event : React.FC<{ event: EventType }> = ({ event }) => {

    const availableSeats = event.availableSeats;

    type ValuePiece = Date | null;

    type Value = ValuePiece | [ValuePiece, ValuePiece];

    const [value, onChange] = useState<Value>(new Date());

    

    return <>
       <div>
            <h1>Event name: {Event.name}</h1>
        </div>
        <div>
            <Calendar onChange={onChange} value={value} />
        </div>
        <div>
            {availableSeats.map(seat => {
                return <Seat day={seat.day} month={seat.month} year={seat.year} seats={seat.count} />
            })}
        </div>
    </>
}

export default Event;