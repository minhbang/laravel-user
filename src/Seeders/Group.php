<?php
namespace Minhbang\User\Seeders;

use DB;
use Minhbang\User\Group as Model;
use Minhbang\Kit\Support\VnString;

/**
 * Class Group
 *
 * @package Minhbang\User\Seeders
 */
class Group
{
    /**
     * @param string $name
     *
     * @return \Minhbang\User\Group
     */
    protected function seedGroupRoot($name = '')
    {
        return Model::firstOrCreate([
            'system_name'  => $name,
            'full_name'    => $name,
            'short_name'   => $name,
            'acronym_name' => $name,
        ]);
    }

    /**
     * @param \Minhbang\User\Group $root
     * @param array $items
     */
    protected function seedGroupItem($root, $items = [])
    {
        foreach ($items as $name => $item) {
            $group = Model::create([
                'system_name'  => VnString::to_slug($name),
                'full_name'    => $name,
                'short_name'   => $item[0],
                'acronym_name' => $item[1],
            ]);
            $group->makeChildOf($root);
            if (isset($item[2]) && is_array($item[2])) {
                $this->seedGroupItem($group, $item[2]);
            }
        }
    }

    /**
     * @param array $data
     */
    public function seed($data = [])
    {
        DB::table('user_groups')->truncate();
        foreach ($data as $type => $groups) {
            $this->seedGroupItem($this->seedGroupRoot($type), $groups);
        }
    }
}