const Level = require('../src/Level1.js')

let level = new Level

test("Level 1", () => {
    expect(level.run()).toEqual(level.getExpectedOutput())
})

