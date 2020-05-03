let fs = require('fs');

let request = JSON.parse(fs.readFileSync('/dev/stdin'));

let color = process.env.EXO__EXAMPLE__COLOR;
let text = request.input.greeting + ', ' + request.input.name + ' (' + color + ')';

let response = {
  "status": "OK",
  "output": {
    "text": text
  }
}

console.log(JSON.stringify(response));
