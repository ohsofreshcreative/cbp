<?php

/**
 * Reguły tego motywu (CBP). W innym projekcie skasuj ten plik
 * albo zostaw 'forbidden_blocks' => [].
 */
return [
	'forbidden_blocks' => [
		[
			'page' => 'b2c',
			'block' => 'solutions',
			'message' => 'Strona B2C: ramka Wehelp z Figmy to blok wehelp, nie solutions. Popraw JSON (resources/imports/b2c.json).',
		],
		[
			'page' => 'b2b',
			'block' => 'wehelp',
			'message' => 'Strona B2B: ramka Solutions z Figmy to blok solutions, nie wehelp. Popraw JSON (resources/imports/b2b.json).',
		],
	],
];
