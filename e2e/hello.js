var page = require('webpage').create();

var url = 'http://move.dev:8000/login';
var email = 'cheezyspaghetti@gmail.com';
var password = 'abcdefg';

casper.test.begin('Exercises are shown', function suite(test) {
    casper.start(url, function() {
        this.echo("I'm loaded.");
    });

    casper.viewport(1024, 768);

    casper.waitForSelector("form input[name='email']", function() {
        this.fillSelectors('form', {
            'input[name = email ]' : email,
            'input[name = password ]' : password
        }, true);
        this.capture('test-img/logged-in.png');
    });

    casper.waitForSelector('#exercises-table', function () {
        casper.waitForText('back lever', function () {
            this.echo('Back lever is on the page');
            casper.wait(100, function () {
                this.capture('test-img/exercises-page.png');
                test.assertVisible('.table');
                this.echo('The exercises table is visible');
            });
        });
    });

    // casper.waitUntilVisible('#something', function () {
    //     this.echo('something is visible');
    //     casper.wait(100, function () {
    //         test.assertTextExists('something');
    //     });
    // });

    casper.run(function() {
        test.done();
        this.echo('The suite ended.');
    });
});































