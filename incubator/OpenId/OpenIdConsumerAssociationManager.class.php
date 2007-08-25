<?php
/***************************************************************************
 *   Copyright (C) 2007 by Anton E. Lebedevich                             *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	interface OpenIdConsumerAssociationManager
	{
		/**
		 * @return OpenIdConsumerAssociation
		**/
		public function findByHandle($handle, $type);
		
		/**
		 * @return OpenIdConsumerAssociation
		**/
		public function findByServer(HttpUrl $server);
		
		/**
		 * @return OpenIdConsumerAssociation
		**/
		public function makeAndSave(
			$handle,
			$type,
			$secred,
			Timestamp $expires,
			HttpUrl $server
		);
		
		/**
		 * @return OpenIdConsumerAssociationManager
		**/
		public function purgeExpired();
		
		/**
		 * @return OpenIdConsumerAssociationManager
		**/
		public function purgeByHandle($handle);
	}
?>