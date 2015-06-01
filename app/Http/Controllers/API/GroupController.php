<?php namespace App\Http\Controllers\API;

use App\Group;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\GroupRepositoryInterface;
use App\Http\Requests\StoreGroupRequest;

/**
 * The controller for managing groups
 */
class GroupController extends Controller
{
    /**
     * The project repository
     *
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * Class constructor
     *
     * @param GroupRepositoryInterface $groupRepository
     * @return void
     */
    public function __construct(GroupRepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * Shows all projects
     *
     * @return Response
     */
    public function index()
    {
        return $this->groupRepository->getAll();
    }

    /**
     * Shows a group
     *
     * @param int $group_id
     * @return Response
     */
    public function show($group_id)
    {
        return $this->groupRepository->getById($group_id);
    }

    /**
     * Store a newly created group in storage.
     *
     * @param StoreGroupRequest $request
     * @return Response
     */
    public function store(StoreGroupRequest $request)
    {
        return $this->groupRepository->create($request->only('name'));
    }

    /**
     * Update the specified group in storage.
     *
     * @param int $group_id
     * @param StoreGroupRequest $request
     * @return Response
     */
    public function update($group_id, StoreGroupRequest $request)
    {
        return $this->groupRepository->updateById($request->only('name'), $group_id);
    }
}