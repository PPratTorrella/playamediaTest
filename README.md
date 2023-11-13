# Pau Prat Torrela - Playamedia test

## Main page
go to home page / and you will clearly see the api calls and two quizes.

Api calls open in new tab, so you can play around modifying api partameters directly in browser if wanted

## Docs
Documentation is at /api/doc.json 

I have kept documentation very simple as an example, can be improved a lot both content and design in app.

## Extra info
I used homeestead, vagrant, virtualbox

Lazyloaded some of the services, for performance when project grows and classes become more complex but currently probably creates too much overhead.

Used Doctrine but for the assignment as it is a json api, did not use ORM for performance.

## Quizes

### Unique permutations
Quiz has commented alternative algorithm which is worse in space complexity (creates more arrays) but is mostly my own idea.
(For knowing how to skip subtrees when duplicates are found, Idid look for extra info online for this type of algorithm).

Then I found better solutions in space complexity, so I implemented a similar (uncommented code, but the original is still there).

### Balanced parenthesis
I understood fast that it could be done linear with stack. 

Haven't compared with online versions, but I am sure that it can there can be tricks for slight improvement found.

Performance numbers are imperfect (specially for memory as garbage collector is hard to predict) but added it anyway as an example of things that could be helpfull.

## Tests
No dedicated database for running tests, for simplicity, but could be done, with seeders and transactions.

For running all tests (Unit, Feature and Integration):

```bash
vendor/bin/phpunit
