const Level = require('../src/Level4.js')

let level = new Level

test("Level 4", () => {
    expect(level.run()).toEqual(level.getExpectedOutput())
})

