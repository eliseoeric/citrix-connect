<?php
/**

	TODO:
	- Change the name to all "aware" classes - should be "contract"
	- Maybe change "entity" to Models as well?

**/

namespace Citirx\Entity;

interface EntityAware {

	public function populate();

}
