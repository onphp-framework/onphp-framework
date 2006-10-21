<?php
/***************************************************************************
 *   Copyright (C) 2005 by Anton E. Lebedevich                             *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	/**
	 * @ingroup Filters
	**/
	class TrimFilter extends BaseFilter
	{
		public function apply($value)
		{
			return trim($value);
		}
	}
?>