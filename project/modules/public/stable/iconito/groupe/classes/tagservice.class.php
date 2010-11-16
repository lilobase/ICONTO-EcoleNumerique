<?php

class tagService extends enicService{

    public function getTagsByGroup($idGroup = 0){
        return $this->db->query('SELECT name, t.id FROM module_tags AS t JOIN module_tags_groups AS gt ON t.id = gt.id_tag WHERE gt.id_group = '.(int)$idGroup)->toArray();
    }

    public function getTagsByGroups($idGroups = array()){
        $groupsCollection = array();

        foreach($idGroups as $idGroup)
            $groupsCollection[$idGroup]['tags'] = $this->getTagsByGroup($idGroup);

        //calculate tags weight for group List
        $tagCollection['tags'] = array();
        foreach($groupsCollection as $group)
            foreach($group['tags'] as $tag)
                if(!isset($tagCollection['tags'][$tag['name']]))
                    $tagCollection['tags'][$tag['name']]['weight'] = 1;
                else
                    $tagCollection['tags'][$tag['name']]['weight']++;

        return $return = array(
            'tags' => $tagCollection['tags'],
            'groups' => $groupsCollection
        );
    }

    public function getGroupsByTag($tagName){
        return $this->db->query('SELECT id_group FROM module_tags_groups WHERE id_tag = '.$this->getTagId($tagName));
    }

    public function addTagsToGroup($idGroup, $tags=array()){
        foreach($tags as $tag){
            $idTag = $this->getTagId($tag);
            if(!$idTag)
                $idTag = $this->addTag($tag);

            $this->db->create('module_tags_groups', array('id_group' => (int)$idGroup, 'id_tag' => $idTag));
        }
    }

    public function getTagId($tagName){
        return $this->db->query('SELECT id FROM module_tags WHERE `name` = '.$this->db->quote($tagName))->toInt();
    }

    public function tagExists($tagName){
        return (bool)$this->getTagId($tagName);
    }

    public function addTag($tagName){
        $this->db->create('module_tags', array('name' => $this->db->quote($tagName)));
        return $this->db->lastId;
    }

    public function deleteTagsOgGroup($idGroup){
        $this->db->delete('module_tags_groups', 'id_group = '.(int)$idGroup);
    }

    public function extractTags($tagsString){
        $tags = explode(',', $tagsString);
        foreach($tags as $k => $tag)
            $return[] = trim($tag);
        return $return;
    }

    public function createTagsString($tags){
        $return = '';
        foreach($tags as $k => $tag){
            $return .= ($k == 0) ? $tag['name'] : ', '.$tag['name'];
        }
        return $return;
    }

    public function createLinkForGroup($idGroup){
        $tags = $this->getTagsByGroup($idGroup);
        $return = '';
        foreach($tags as $k => $tag){
            $link = $this->url('groupe|default|getListPublic', array('kw' => $tag['name']));
            $return .= ($k == 0) ? '<a href="'.$link.'">'.$tag['name'].'</a>' : ', <a href="'.$link.'">'.$tag['name'].'</a>';
        }
        return (!empty($return)) ? '<strong>Tags : </strong>'.$return : '';
    }
}