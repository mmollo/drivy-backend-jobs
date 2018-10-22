const Level = require('../src/Level2.js')

let level = new Level

test("Level 2", () => {
    expect(level.run()).toEqual(level.getExpectedOutput())
})

