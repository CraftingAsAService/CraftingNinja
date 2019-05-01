const mix = require('laravel-mix');
const fs = require('fs');
const junk = require('junk');

let getFiles = function (dir) {
	// get all 'files' in this directory
	// filter directories
	return fs.readdirSync(dir).filter(file => {
		return fs.statSync(`${dir}/${file}`).isFile();
	}).filter(junk.not);
};

getFiles('resources/js/pages').forEach(function (filepath) {
	mix.js('resources/js/pages/' + filepath, 'public/js/pages');
});

mix.sass('resources/scss/app.scss', 'public/css');

mix.js('resources/js/app.js', 'public/js')
	.extract(['vue']);

if (mix.inProduction()) {
	mix.version();
}
