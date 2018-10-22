const Level = require('./Level.js')
class Level3 extends Level
{
    constructor()
    {
        super(3)
    }

    run()
    {
        let rentals = this.input.rentals.map(rental => {
            let duration = this.getDuration(rental)
            let car = this.cars[rental.car_id]
            let price = this.getDurationPrice(duration, car.price_per_day) + rental.distance * car.price_per_km
            let commission = this.getCommission(price, duration)
            
            return {
                id: rental.id,
                price: price,
                commission: commission
            }
        })

        return {rentals: rentals}
    }

    getCommission(price, duration)
    {
        price *= 0.3;
        return {
            'insurance_fee': price * 0.5,
            'assistance_fee': duration * 100,
            'drivy_fee': price * 0.5 - duration * 100
        }
    }

    getDurationPrice(duration, price_per_day)
    {
        let steps = [
            [1, 10],
            [4, 30],
            [10, 50]
        ]

        var price = 0
        var days, reduction;
        steps.reverse().forEach(step => {
            days = step[0]
            reduction = step[1]
            if(duration <= days) {
                return
            }

            price += (duration - days) * price_per_day * (100 - reduction) / 100;
            duration -= (duration - days);
        })

        return price + duration * price_per_day

    }
}

module.exports = Level3