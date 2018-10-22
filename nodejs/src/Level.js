const DateDiff = require('date-diff');

class Level
{
    constructor(level) {
        this.level = level
        this.input = require('../../jobs/backend/level' + level + '/data/input.json')
        this.expected_output = require('../../jobs/backend/level' + level + '/data/expected_output.json')

        this.cars = {}
        this.input.cars.forEach(car => {
            this.cars[car.id] = car
        })
    }

    getExpectedOutput() {
        return this.expected_output
    }

    // To fix the weird formatting from Ruby
    fixDuration(duration) {
        return duration.split('-').map(e => {
            return e.length < 2 ? '0' + e : e
        }).join('-')
    }
    getDuration(rental) {
        return (new DateDiff(
            new Date(this.fixDuration(rental.end_date)),
            new Date(this.fixDuration(rental.start_date))
        )).days() + 1
    }

    setCars() {
        let cars = {}

    }
}

module.exports = Level