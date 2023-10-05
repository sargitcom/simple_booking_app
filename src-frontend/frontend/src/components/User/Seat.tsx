import React from 'react';

const Seat : React.FC<{ day: number, month: number, year: number, seats: number }> = ({ day, month, year, seats }) => {
    return <>
        <div className={'seat'}>
            <div className={'seat-year'}>
                <p>{year}</p>
            </div>
            <div className={'seat-day-month'}>
                <p>{day}-{month}</p>
            </div>
            <div className={'seat-seats-number'}>
                {seats}
            </div>
        </div>
    </>
}

export default Seat;