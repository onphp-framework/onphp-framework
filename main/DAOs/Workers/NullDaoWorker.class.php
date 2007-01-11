<?php
/***************************************************************************
 *   Copyright (C) 2005-2006 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	/**
	 * Cacheless DAO worker.
	 * 
	 * @see CommonDaoWorker for manual-caching one.
	 * @see SmartDaoWorker for transparent one.
	 * 
	 * @ingroup DAOs
	**/
	final class NullDaoWorker extends BaseDaoWorker
	{
		//@{
		// single object getters
		public function get(ObjectQuery $oq)
		{
			return $this->getByQuery($oq->toSelectQuery($this->dao));
		}
		
		public function getById($id)
		{
			$query = 
				$this->dao->makeSelectHead()->
				where(
					Expression::eq(
						DBField::create('id', $this->dao->getTable()),
						$id
					)
				);

			if ($object = $this->fetchObject($query))
				return $object;
			else
				throw new ObjectNotFoundException();
			
			/* NOTREACHED */
		}
		
		public function getByLogic(LogicalObject $logic)
		{
			return
				$this->getByQuery(
					$this->dao->makeSelectHead()->where($logic)
				);
		}
		
		public function getByQuery(SelectQuery $query)
		{
			if ($object = $this->fetchObject($query))
				return $object;
			else
				throw new ObjectNotFoundException();
			
			/* NOTREACHED */
		}
		
		public function getCustom(SelectQuery $query)
		{
			if ($query->getLimit() > 1)
				throw new WrongArgumentException(
					'can not handle non-single row queries'
				);

			$custom = DBPool::getByDao($this->dao)->queryRow($query);
			
			if ($custom)
				return $this->cacheByQuery($query, $custom);
			else
				throw new ObjectNotFoundException();
			
			/* NOTREACHED */
		}
		//@}
		
		//@{
		// object's list getters
		public function getList(ObjectQuery $oq)
		{
			return $this->getListByQuery($oq->toSelectQuery($this->dao));
		}
		
		public function getListByIds($ids)
		{
			$list = array();
			
			foreach ($ids as $id) {
				try {
					$list[] = $this->getById($id);
				} catch (ObjectNotFoundException $e) {
					// ignore
				}
			}

			return $list;
		}
		
		public function getListByQuery(SelectQuery $query)
		{
			if ($list = $this->fetchList($query))
				return $list;
			else
				throw new ObjectNotFoundException();
			
			/* NOTREACHED */
		}
		
		public function getListByCriteria(Criteria $criteria)
		{
			if ($list = $this->fetchList($criteria->toSelectQuery()))
				return $list;
			else
				throw new ObjectNotFoundException();
			
			/* NOTREACHED */
		}
		
		public function getListByLogic(LogicalObject $logic)
		{
			return $this->getListByQuery($this->dao->makeSelectHead()->where($logic));
		}
		
		public function getPlainList()
		{
			return $this->getListByQuery($this->dao->makeSelectHead());
		}
		//@}

		//@{
		// custom list getters
		public function getCustomList(
			SelectQuery $query, $expres = Cache::DO_NOT_CACHE
		)
		{
			if ($list = DBPool::getByDao($this->dao)->querySet($query))
				return $list;
			else
				throw new ObjectNotFoundException();
		}
		
		public function getCustomRowList(
			SelectQuery $query, $expires = Cache::DO_NOT_CACHE
		)
		{
			if ($query->getFieldsCount() !== 1)
				throw new WrongArgumentException(
					'you should select only one row when using this method'
				);
			
			if ($list = DBPool::getByDao($this->dao)->queryColumn($query))
				return $list;
			else
				throw new ObjectNotFoundException();
		}
		//@}
		
		//@{
		// query result getters
		public function getCountedList(ObjectQuery $oq)
		{
			return $this->getQueryResult($oq->toSelectQuery($this->dao));
		}
		
		public function getQueryResult(SelectQuery $query)
		{
			$list = $this->fetchList($query);
			
			$count = clone $query;
			
			$count =
				$db->queryRow(
					$count->dropFields()->dropOrder()->limit(null, null)->
					get(SQLFunction::create('COUNT', '*')->setAlias('count'))
				);

			$res = new QueryResult();

			return
				$res->
					setList($list)->
					setCount($count['count'])->
					setQuery($query);
		}
		//@}

		//@{
		// erasers
		public function dropById($id)
		{
			return
				DBPool::getByDao($this->dao)->queryNull(
					OSQL::delete()->from($this->dao->getTable())->
					where(Expression::eq('id', $id))
				);
		}
		
		public function dropByIds(/* array */ $ids)
		{
			return 
				DBPool::getByDao($this->dao)->queryNull(
					OSQL::delete()->from($this->dao->getTable())->
					where(Expression::in('id', $ids))
				);
		}
		//@}
		
		//@{
		// cachers
		public function cacheById(Identifiable $object)
		{
			return $object;
		}
		
		public function cacheByQuery(
			SelectQuery $query, /* Identifiable */ $object
		)
		{
			return $object;
		}
		
		public function cacheListByQuery(SelectQuery $query, /* array */ $array)
		{
			return $array;
		}
		//@}
		
		//@{
		// uncachers
		public function uncacheById($id)
		{
			return true;
		}
		
		public function uncacheByIds($ids)
		{
			return true;
		}
		
		public function uncacheLists()
		{
			return true;
		}
		//@}
		
		//@{
		// cache getters
		public function getCachedById($id)
		{
			return null;
		}
		
		public function getCachedByQuery(SelectQuery $query)
		{
			return null;
		}
		//@}
	}
?>