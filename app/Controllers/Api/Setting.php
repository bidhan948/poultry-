<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Shed;
use App\Models\Group;
use App\Models\FeedType;
use App\Models\PoultryType;
use App\Models\Breed;
use App\Models\StandardBreederPerformance;
use App\Models\StandardHatcheryInformation;
use App\Models\StandartBreederInformation;
use App\Models\MedicineVaccine;
use App\Models\Unit;
use App\Models\Remarks;


class Setting extends ResourceController
{
    use ResponseTrait;

    // all sheds
    public function getAllSheds()
    {
        $model = new Shed();
        $data = $model->getAllShedsWithGroup();
        return $this->respond($data);
    }
    // create or update
    public function addOrUpdateShed($id = null)
    {
        $model = new Shed();
        $data = [
            'name' => $this->request->getVar('name'),
            'groupId'  => $this->request->getVar('groupId'),
            'description'  => $this->request->getVar('description'),
        ];
        $id = $this->request->getVar('id');
        if (empty($id)) {
            $model->insert($data);
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => 'Shed Created Successfully'
            ];
        } else {
            $model->update($id, $data);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Shed Updated Successfully'
            ];
        }
        return $this->respond($response);
    }

    // delete
    public function deleteShed($id = null)
    {
        $model = new Shed();
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Shed Deleted Successfully'
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No shed found');
        }
    }
    //*************************************************************************************************************** */
    // all units
    public function getAllUnits()
    {
        $model = new Unit();
        $data = $model->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }
    // create or update
    public function addOrUpdateUnit($id = null)
    {
        $model = new Unit();
        $data = [
            'name' => $this->request->getVar('name'),
            'description'  => $this->request->getVar('description'),
        ];
        $id = $this->request->getVar('id');
        if (empty($id)) {
            $model->insert($data);
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => 'Unit Created Successfully'
            ];
        } else {
            $model->update($id, $data);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Unit Updated Successfully'
            ];
        }
        return $this->respond($response);
    }

    // delete
    public function deleteUnit($id = null)
    {
        $model = new Unit();
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'UNit Deleted Successfully'
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No shed found');
        }
    }
    //*************************************************************************************************************** */
    // all remarks
    public function getAllRemarks()
    {
        $model = new Remarks();
        $data = $model->getAllReamrksTypeWithUnit();
        return $this->respond($data);
    }
    // all active remarks
    public function getAllActiveRemarks()
    {
        $model = new Remarks();
        $data = $model->getAllReamrksTypeWithUnitWithActiveStatus();
        return $this->respond($data);
    }
    // create or update
    public function addOrUpdateRemarks($id = null)
    {
        $model = new Remarks();
        $data = [
            'name' => $this->request->getVar('name'),
            'unitId' => $this->request->getVar('unitId'),
            'status' => $this->request->getVar('status'),
            'description'  => $this->request->getVar('description'),
        ];
        $id = $this->request->getVar('id');
        if (empty($id)) {
            $model->insert($data);
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => 'Remarks Created Successfully'
            ];
        } else {
            $model->update($id, $data);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Remarks Updated Successfully'
            ];
        }
        return $this->respond($response);
    }

    // delete
    public function deleteRemarks($id = null)
    {
        $model = new Remarks();
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Remarks Deleted Successfully'
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No shed found');
        }
    }


    //*************************************************************************************************************** */
    // all MedicineVaccines
    public function getAllMedicineVaccines()
    {
        $model = new MedicineVaccine();
        $data = $model->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }

    // create or update
    public function addOrUpdateMedicineVaccine($id = null)
    {
        $model = new MedicineVaccine();
        $data = [
            'name' => $this->request->getVar('name'),
            'physicalForm' => $this->request->getVar('physicalForm'),
            'description'  => $this->request->getVar('description'),
        ];
        $id = $this->request->getVar('id');
        if (empty($id)) {
            $model->insert($data);
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => 'Medicine/Vaccine Created Successfully'
            ];
        } else {
            $model->update($id, $data);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Medicine/Vaccine Updated Successfully'
            ];
        }
        return $this->respond($response);
    }

    // delete
    public function deleteMedicineVaccine($id = null)
    {
        $model = new MedicineVaccine();
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Medicine/Vaccine Deleted Successfully'
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Medicine/Vaccine found');
        }
    }



    //*************************************************************************************************************** */
    // all FeedType
    public function getAllFeedTypes()
    {
        $model = new FeedType();
        $data = $model->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }
    // create FeedType
    public function addFeedType()
    {
        $model = new FeedType();
        $data = [
            'name' => $this->request->getVar('name'),
            'description'  => $this->request->getVar('description'),
        ];
        $model->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => 'FeedType Created Successfully'
        ];
        return $this->respondCreated($response);
    }

    // create or update FeedType
    public function addOrUpdateFeedType($id = null)
    {
        $model = new FeedType();
        $data = [
            'name' => $this->request->getVar('name'),
            'description'  => $this->request->getVar('description'),
        ];
        $id = $this->request->getVar('id');
        if (empty($id)) {
            $model->insert($data);
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => 'FeedType Created Successfully'
            ];
        } else {
            $model->update($id, $data);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'FeedType Updated Successfully'
            ];
        }
        return $this->respond($response);
    }

    // delete FeedType
    public function deleteFeedType($id = null)
    {
        $model = new FeedType();
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'FeedType Deleted Successfully'
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No FeedType found');
        }
    }



    //*************************************************************************************************************** */
    // all PoultryType
    public function getAllPoultryTypes()
    {
        $model = new PoultryType();
        $data = $model->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }
    // create PoultryType
    public function addPoultryType()
    {
        $model = new PoultryType();
        $data = [
            'name' => $this->request->getVar('name'),
            'description'  => $this->request->getVar('description'),
        ];
        $model->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => 'PoultryType Created Successfully'
        ];
        return $this->respondCreated($response);
    }

    // create or update PoultryType
    public function addOrUpdatePoultryType($id = null)
    {
        $model = new PoultryType();
        $data = [
            'name' => $this->request->getVar('name'),
            'description'  => $this->request->getVar('description'),
        ];
        $id = $this->request->getVar('id');
        if (empty($id)) {
            $model->insert($data);
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => 'PoultryType Created Successfully'
            ];
        } else {
            $model->update($id, $data);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'PoultryType Updated Successfully'
            ];
        }
        return $this->respond($response);
    }

    // delete PoultryType
    public function deletePoultryType($id = null)
    {
        $model = new PoultryType();
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'PoultryType Deleted Successfully'
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No PoultryType found');
        }
    }

    //****************************************************************************************************************************************************************** */

    // all BREEDs
    public function getAllBreeds()
    {
        $model = new Breed();
        $data = $model->getAllBreedsWithPoultryType();
        return $this->respond($data);
    }
    // create or update
    public function addOrUpdateBreed($id = null)
    {
        $model = new Breed();
        $data = [
            'name' => $this->request->getVar('name'),
            'poultryTypeId'  => $this->request->getVar('poultryTypeId'),
            'description'  => $this->request->getVar('description'),
        ];
        $id = $this->request->getVar('id');
        if (empty($id)) {
            $model->insert($data);
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => 'Breed Created Successfully'
            ];
        } else {
            $model->update($id, $data);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Breed Updated Successfully'
            ];
        }
        return $this->respond($response);
    }

    // delete
    public function deleteBreed($id = null)
    {
        $model = new Breed();
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Breed Deleted Successfully'
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Breed found');
        }
    }

    //*************************************************************************************************************** */
    // all Group
    public function getAllGroups()
    {
        $model = new Group();
        $data = $model->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }

    // create or update
    public function addOrUpdateGroup($id = null)
    {
        $model = new Group();
        $data = [
            'name' => $this->request->getVar('name'),
            'description'  => $this->request->getVar('description'),
        ];
        $id = $this->request->getVar('id');
        if (empty($id)) {
            $model->insert($data);
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => 'Group Created Successfully'
            ];
        } else {
            $model->update($id, $data);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Group Updated Successfully'
            ];
        }
        return $this->respond($response);
    }

    // delete
    public function deleteGroup($id = null)
    {
        $model = new Group();
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Group Deleted Successfully'
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Group found');
        }
    }

    //*************************************************************************************************************** */

    // all StandardBreederPerformance
    public function getAllStandardBreederPerformances()
    {
        $model = new StandardBreederPerformance();
        $data = $model->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }
    // create or update
    public function addOrUpdateStandardBreederPerformance($id = null)
    {
        $model = new StandardBreederPerformance();
        $data = [
            'ageInWeeks' => $this->request->getVar('ageInWeeks'),
            'totalEggsPercentageHw'  => $this->request->getVar('totalEggsPercentageHw'),
            'hatchingEggsPercentageHw'  => $this->request->getVar('hatchingEggsPercentageHw'),
            'mortalityCumPercentage'  => $this->request->getVar('mortalityCumPercentage'),
            'percentageHeWeekly'  => $this->request->getVar('percentageHeWeekly'),
            'totalEggsHh'  => $this->request->getVar('totalEggsHh'),
            'hatchingEggsHh'  => $this->request->getVar('hatchingEggsHh'),
            'hhhe'  => $this->request->getVar('hhhe'),
            'henHouseNumber'  => $this->request->getVar('henHouseNumber'),
            'feedConversionRatio'  => $this->request->getVar('feedConversionRatio'),
        ];
        $id = $this->request->getVar('id');
        if (empty($id)) {
            $model->insert($data);
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => 'Standard Breeder Performance Created Successfully'
            ];
        } else {
            $model->update($id, $data);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Standard Breeder Performance Updated Successfully'
            ];
        }
        return $this->respond($response);
    }

    // delete
    public function deleteStandardBreederPerformance($id = null)
    {
        $model = new StandardBreederPerformance();
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Standard Breeder Performance Deleted Successfully'
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Breed found');
        }
    }

    //*************************************************************************************************************** */

    // all StandardHatcheryInformation
    public function getAllStandardHatcheryInformations()
    {
        $model = new StandardHatcheryInformation();
        $data = $model->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }
    // create or update
    public function addOrUpdateStandardHatcheryInformation($id = null)
    {
        $model = new StandardHatcheryInformation();
        $data = [
            'ageInWeeks' => $this->request->getVar('ageInWeeks'),
            'fertilityPercentage'  => $this->request->getVar('fertilityPercentage'),
            'hatchabilityPercentage'  => $this->request->getVar('hatchabilityPercentage'),
            'embInfertilePercentage'  => $this->request->getVar('embInfertilePercentage'),
            'embEarlyPercentage'  => $this->request->getVar('embEarlyPercentage'),
            'embMidPercentage'  => $this->request->getVar('embMidPercentage'),
            'embLatePercentage'  => $this->request->getVar('embLatePercentage'),
            'hofPercentage'  => $this->request->getVar('hofPercentage'),
        ];
        $id = $this->request->getVar('id');
        if (empty($id)) {
            $model->insert($data);
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => 'Standard Hatchery Information Created Successfully'
            ];
        } else {
            $model->update($id, $data);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Standard Hatchery Information Updated Successfully'
            ];
        }
        return $this->respond($response);
    }

    // delete
    public function deleteStandardHatcheryInformation($id = null)
    {
        $model = new StandardHatcheryInformation();
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Standard Hatchery Information Deleted Successfully'
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Breed found');
        }
    }
    //*************************************************************************************************************** */

    // all StandartBreederInformation
    public function getAllStandartBreederInformations()
    {
        $model = new StandartBreederInformation();
        $data = $model->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }
    // create or update
    public function addOrUpdateStandartBreederInformation($id = null)
    {
        $model = new StandartBreederInformation();
        $data = [
            'ageInWeeks' => $this->request->getVar('ageInWeeks'),
            'hatchabilityWeekly'  => $this->request->getVar('hatchabilityWeekly'),
            'hatchabilityCum'  => $this->request->getVar('hatchabilityCum'),
            'fertilityWeekly'  => $this->request->getVar('fertilityWeekly'),
            'fertilityCum'  => $this->request->getVar('fertilityCum'),
            'hatchOfFertilesWeekly'  => $this->request->getVar('hatchOfFertilesWeekly'),
            'hatchOfFertilesCum'  => $this->request->getVar('hatchOfFertilesCum'),
            'chickNoHenHousedWeekly'  => $this->request->getVar('chickNoHenHousedWeekly'),
            'chickNoHenHousedCum'  => $this->request->getVar('chickNoHenHousedCum'),
            'chickWeightGram'  => $this->request->getVar('chickWeightGram'),
        ];
        $id = $this->request->getVar('id');
        if (empty($id)) {
            $model->insert($data);
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => 'Standard Breeder Information Created Successfully'
            ];
        } else {
            $model->update($id, $data);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Standard Breeder Information Updated Successfully'
            ];
        }
        return $this->respond($response);
    }

    // delete
    public function deleteStandartBreederInformation($id = null)
    {
        $model = new StandartBreederInformation();
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Standard Breeder Information Deleted Successfully'
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Breed found');
        }
    }
}
