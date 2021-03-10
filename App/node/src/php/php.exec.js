/*
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/

class ExecPHP {

	phpPath = 'php';

	/**
	 *
	 */
	constructor(){
	}

	setPhpPath(phpPath){
		this.phpPath = phpPath;
		return this;
	}

	/**
	 *
	 */
	parseFile(fileName, callback) {
		var exec = require('child_process').exec;
		var cmd = this.phpPath + ' ' + fileName;
		
		exec(cmd, function(error, stdout, stderr) {
			callback(error, stdout, stderr);
		});
	}
}
module.exports = function() {
	return new ExecPHP();
};