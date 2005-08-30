<?php
/***************************************************************************
 *   Copyright (C) 2004-2005 by Konstantin V. Arkhipov, Anton Lebedevich   *
 *   voxus@shadanakar.org                                                  *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */
 
	class BaseException extends Exception
	{
		
		public function __construct($message = null, $code = 0, $file = null, $line = null)
		{
			parent::__construct($message, $code);
			
			if ($file)
				$this->file		= $file;

			if ($line)
				$this->line		= $line;
		}
		
		function __toString()
		{
			return
				"[$this->message] in: \n".
				var_export($this->trace, true);
		}
	}
?>
