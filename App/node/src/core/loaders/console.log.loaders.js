/*!
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
module.exports = function(source, map) {
    this.callback(
        null,
        source.replace(/console\.log\(.*\);?\n/g, ''),
        map
    );
};