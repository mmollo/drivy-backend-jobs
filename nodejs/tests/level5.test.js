const Level = require('../src/Level5.js')

let level = new Level

test("Level 5", () => {
    expect(level.run()).toEqual(level.getExpectedOutput())
})

