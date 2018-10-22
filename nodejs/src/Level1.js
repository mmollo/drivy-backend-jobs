const Level = require('./Level.js')
class Level1 extends Level
{
    constructor()
    {
        super(1)
    }

    run()
    {
        let rentals = this.input.rentals.map(rental => {
            let duration = this.getDuration(rental)
            let car = this.cars[rental.car_id]
            
            return {
                id: rental.id,
                price: duration * car.price_per_day + rental.distance * car.price_per_km
            }
        })

        return {rentals: rentals}
    }
}

module.exports = Level1