const Level = require('../src/Level3.js')

let level = new Level

test("Level 3", () => {
    expect(level.run()).toEqual(level.getExpectedOutput())
})

