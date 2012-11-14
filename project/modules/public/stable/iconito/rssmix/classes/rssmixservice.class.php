<?php

class rssmixService extends enicService
{
    public function addRssUrl($url, $title, $image)
    {
        $imageName = '';

        if (!empty($image)) {
            enic::to_load('image');
            $imageClass = new enicImage();
            $imageName = $imageClass->upload($image);
        }

        return $this->db->create('module_rssmix', array('url' => $this->db->quote($url), 'title' => $this->db->quote($title), 'image' => $this->db->quote($imageName)));
    }

    public function deleteRssUrl($id)
    {
        $originaImageId = $this->db->query('SELECT image FROM module_rssmix WHERE id = ' . (int) $id)->toArray1();

        if (!empty($originaImageId['image'])) {
            $imageClass = new enicImage();
            $imageClass->delete($originaImageId['image']);
        }

        return $this->db->delete('module_rssmix', (int) $id);
    }

    public function getRssUrl($id = null)
    {
        $where = (is_null($id)) ? '' : ' WHERE id = ' . (int) $id;

        return $this->db->query('SELECT * FROM module_rssmix' . $where)->toArray();
    }

    public function updateRssUrl($id, $url, $title, $image)
    {
        $imageName = $this->db->query('SELECT image FROM module_rssmix WHERE id = ' . (int) $id)->toArray1();
        $imageName = $imageName['image'];

        if (!empty($image)) {

            $imageClass = new enicImage();
            if (!empty($imageName)) {
                $imageClass->delete($imageName);
            }

            $imageName = $imageClass->upload($image);
        }


        return $this->db->update('module_rssmix', array('url' => $this->db->quote($url), 'id' => (int) $id, 'title' => $this->db->quote($title), 'image' => $this->db->quote($imageName)));
    }

    public function deleteImage($id)
    {
         $imageName = $this->db->query('SELECT image FROM module_rssmix WHERE id = ' . (int) $id)->toArray1();
         $imageName = $imageName['image'];

            $imageClass = new enicImage();
            if (!empty($imageName)) {
                $imageClass->delete($imageName);
            }

        return $this->db->update('module_rssmix', array('id' => $id, 'image' => '\'\''));
    }

    public function __construct()
    {
        $this->feed = & enic::get('zend');
        enic::to_load('image');

        parent::__construct();
    }

    public function getRssFeeds()
    {
        $urls = $this->getRssUrl();

        if (empty($urls))
            return null;

        $item = array();

        $globalLimit = $this->helpers->config('rssmix|itemsLimit');

        $limit = round($globalLimit / count($urls));

        foreach ($urls as $url) {
            $title = (isset($url['title'])?$url['title']:'');

            try {
            $imagePath = '';
            if(!empty($url['image'])){
                $iC = new enicImage();
                $imagePath = $iC->get($url['image'], 25, 25, 'crop');
            }} catch (Exception $e) {
                // Au cas où l'image n'existe plus
            }
            $rss = $this->getRssFeed($url['url'], $limit);
            foreach ($rss as $key => $currentRss){
                $rss[$key]['img'] = $imagePath;
                $rss[$key]['fluxTitle'] = $title;
            }

            $item = array_merge($rss, $item);
        }

        return $item;
    }

    public function getRssFeed($url, $limit = 3)
    {
        $feed = Zend_Feed_Reader::import($url);

        $iterator = 1;
        $item = array();

        $img = $feed->getImage();

        foreach ($feed as $entry) {

            if ($iterator > $limit)
                break;

            $item[$iterator]['title'] = $this->helpers->word_cut($entry->getTitle(), 100);
            $item[$iterator]['link'] = $entry->getLink();
            $item[$iterator]['content'] = $entry->getContent();
            $item[$iterator]['img'] = $img;

            $iterator++;
        }
        return $item;
    }
    
    /**
     * return truc if any feed is define;
     * @return bool 
     */
    public function getRssIsNotEmpty()
    {
        $test = $this->db->query('SELECT * FROM module_rssmix LIMIT 1')->toArray1();
        return (!empty($test));
    }

}
