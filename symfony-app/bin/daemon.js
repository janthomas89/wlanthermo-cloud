var http = require('http');
var path = require('path');
var url = require('url');
var qs = require('querystring');
var util = require('util');
var cp = require('child_process');
var fs = require('fs');

// @see http://labs.telasocial.com/nodejs-forever-daemon/
// forever -o app/logs/nodejs-daemon.log -e app/logs/nodejs-daemon.log bin/daemon.js

/* Hashmap for running sub processes */
var processes = {};

/* Port */
var port = 1025;

/* Create server */
var server = http.createServer(function (req, resp) {
    var parsedUrl = url.parse(req.url);
    var params = qs.parse(parsedUrl.query);

    if ('/spawn' === parsedUrl.pathname && params.measurementId) {
        var php = spawn(params.measurementId);

        jsonResponse(resp, 200, {
            pid: php.pid,
            msg: 'process with pid ' + php.pid + ' started for measurement '
        });
    } else if ('/kill' === parsedUrl.pathname && params.measurementId) {
        var res = kill(params.measurementId);

        jsonResponse(resp, res ? 200 : 404, {
            status: res,
            msg: 'killing process for measurement ' + params.measurementId
        });
    } else if ('/list' === parsedUrl.pathname) {
        tmpProcesses = {};
        for (id in processes) {
            tmpProcesses[id] = processes[id].pid;
        }

        jsonResponse(resp, 200, {
            status: 200,
            msg: 'List all processes measurementId => pid',
            processes: tmpProcesses
        });
    } else {
        jsonResponse(resp, 404, {
            msg: 'Not found. Try /spawn?measurementId=123 or /kill?measurementId=123'
        });
    }
});
server.listen(port, 'localhost');
console.log('wlanthermo daemon server now listens on port ' + port);

/* Span process */
function spawn(measurementId) {
    if (processes[measurementId]) {
        kill(measurementId);
    }

    var php  = cp.spawn('php', [__dirname + '/../app/console', 'app:measurement', measurementId]);
    php.stdout.on('data', function (data) {
        util.log('[OUT pid ' + php.pid + ', measurementId ' + measurementId + ']: ' + data);
    });
    php.stderr.on('data', function (data) {
        util.log('[ERR pid ' + php.pid + ', measurementId ' + measurementId + ']: ' + data);
    });
    php.on('exit', function (code) {
        util.log('[EXIT pid ' + php.pid + ', measurementId ' + measurementId + ']: code ' + code);
    });

    setTimeout(function() {
        processes[measurementId] = php;
    }, 0);

    return php;
};

/* Kill process */
var kill = function(measurementId) {
    var php = processes[measurementId];
    if (php) {
        php.kill('SIGINT');
        delete processes[measurementId];
        return true;
    } else {
        return false;
    }
};

/* Output a JSON response */
var jsonResponse = function(resp, status, obj) {
    resp.writeHead(status, {
        'Content-Type': 'application/json; charset=utf-8'
    });
    obj.status = status;
    resp.write(JSON.stringify(obj));
    resp.end();
};

/* Clean shutdown */
var onExit = function () {
    processes && util.log('wlanthermo daemon server shuts down');

    for (var key in processes) {
        if (processes.hasOwnProperty(key)) {
            processes[key].kill('SIGINT');
        }
    }

    processes = null;

    process.exit(0);
};
process.on('SIGINT', onExit);
process.on('exit', onExit);
