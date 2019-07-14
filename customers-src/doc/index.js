const express = require('express');
const path = require('path');
const app = express();

app.use(express.static(path.join(__dirname, './')));

app.listen(9002, function() {
    console.log('Doc server run at localhost:9002, open browser - http://localhost:9002');
});