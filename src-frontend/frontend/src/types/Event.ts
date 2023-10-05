import Seat from "./Seat";

type Event = {
    name: string,
    availableSeats: Seat[]
    reservedSeats: Seat[]
}

export default Event;