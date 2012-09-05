<?php

class tagService extends enicService
{
    public function getTagsByGroup($idGroup = 0)
    {
        return $this->db->query('SELECT name, t.id FROM module_tags AS t JOIN module_tags_groups AS gt ON t.id = gt.id_tag WHERE gt.id_group = '.(int)$idGroup)->toArray();
    }

    public function getTagsByGroups($idGroups = array())
    {
        $groupsCollection = array();

        foreach($idGroups as $idGroup)
            $groupsCollection[$idGroup]['tags'] = $this->getTagsByGroup($idGroup);

        //calculate tags weight for group List
        $tagCollection = array();
    $max = 1;
        foreach($groupsCollection as $group){
            foreach($group['tags'] as $tag)
                if(!isset($tagCollection[$tag['name']]))
                    $tagCollection[$tag['name']] = 1;
                else{
                    $tagCollection[$tag['name']]++;

            if($tagCollection[$tag['name']] > $max)
            $max = $tagCollection[$tag['name']];
        }
    }

    //ponderate between 1 and 100
    $ponde = 100/$max;
    $ponderation = CopixConfig::get ('groupe|ponderation');
    foreach($tagCollection as $tagName => $weight){
        $tagCollection[$tagName] = round(($weight*$ponde)/$ponderation);
    }

        return $return = array(
            'tags' => $tagCollection,
            'groups' => $groupsCollection
        );
    }

    public function createTagsCloud($idGroups = array())
    {
    $tagsList = $this->getTagsByGroups($idGroups);
    $tagsList = $tagsList['tags'];

    $tagsFinalArray = array();
    foreach($tagsList as $name => $weight){
        $tagsFinalArray[] = $this->createLinkForTag($name, $weight);
    }

    return $tagsFinalArray;
    }

    public function getGroupsByTag($tagName)
    {
        return $this->db->query('SELECT id_group FROM module_tags_groups WHERE id_tag = '.$this->getTagId($tagName));
    }

    public function addTagsToGroup($idGroup, $tags=array())
    {
        foreach($tags as $tag){
            $idTag = $this->getTagId($tag);
            if(!$idTag)
                $idTag = $this->addTag($tag);

            $this->db->create('module_tags_groups', array('id_group' => (int)$idGroup, 'id_tag' => $idTag));
        }
    }

    public function getTagId($tagName)
    {
        return $this->db->query('SELECT id FROM module_tags WHERE `name` = '.$this->db->quote($tagName))->toInt();
    }

    public function tagExists($tagName)
    {
        return (bool)$this->getTagId($tagName);
    }

    public function addTag($tagName)
    {
        $this->db->create('module_tags', array('name' => $this->db->quote($tagName)));
        return $this->db->lastId;
    }

    public function deleteTagsOgGroup($idGroup)
    {
        $this->db->delete('module_tags_groups', 'id_group = '.(int)$idGroup);
    }

    public function extractTags($tagsString)
    {
        $tags = explode(',', $tagsString);
        foreach($tags as $k => $tag)
            $return[] = trim($tag);
        return $return;
    }

    public function createTagsString($tags)
    {
        $return = '';
        foreach($tags as $k => $tag){
            $return .= ($k == 0) ? $tag['name'] : ', '.$tag['name'];
        }
        return $return;
    }

    public function createLinkForTag($name, $weight = null)
    {
    $link = $this->url('groupe|default|getListPublic', array('kw' => $name));
    $class = (empty($weight)) ? '' : 'class = "tagGroup tagWeight'.$weight.'"';
    return '<a href="'.$link.'" '.$class.'>'.$name.'</a>';
    }

    public function createLinkForGroup($idGroup)
    {
        $tags = $this->getTagsByGroup($idGroup);
        $return = '';
        foreach($tags as $k => $tag){
            $return .= ($k == 0) ? $this->createLinkForTag($tag['name']) : ', '.$this->createLinkForTag($tag['name']);
        }

        return (!empty($return)) ? '<strong>Tags : </strong>'.$return : '';
    }
}