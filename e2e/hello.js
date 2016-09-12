var page = require('webpage').create();

var url = 'http://move.dev:8000/auth/login';
var email = 'cheezyspaghetti@gmail.com';
var password = 'abcdefg';

casper.test.begin('Exercises are shown', 3, function suite(test) {
    casper.start(url, function() {
        this.echo("I'm loaded.");
    });

    casper.viewport(1024, 768);

    casper.waitForSelector("form input[name='email']", function() {
        this.fillSelectors('form', {
            'input[name = email ]' : email,
            'input[name = password ]' : password
        }, true);

    });

    casper.waitForSelector('#exercises .table-bordered', function () {
        // this.capture('test.png');
        this.echo('should be testing');
        test.assertTextExists('back lever');
    });

    casper.run(function() {
        test.done();
        this.echo('The suite ended.');
    });
});





