<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\DataModel;
use App\Models\NotificationSender;
use App\Models\Translate;
use Picqer\src\BarcodeGeneratorPNG;

class Admin extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = session();
        $this->notificationsender = new NotificationSender();
        $this->translate = new Translate();
        $this->admin_id = $this->session->get("user_id");
        $this->login_type = $this->session->get("login_type");
        $this->language = $this->db->table("admin")->where("id", $this->admin_id)->get()->getRow()->language ?? null;
        if ($this->admin_id) {$DataModel = new DataModel();$this->data = $DataModel;}
    }
    
    protected function checkAdmin()
    {
        if ($this->login_type !== "Admin") {
            return redirect()->to(base_url())->send();
        }
    }

    public function page($name,$title,$page_data = [],$landing_page = "index")
    {
        $this->checkAdmin();
        $page_data["db"] = $this->db;
        $page_data["language"] = $this->language;
        $page_data["translate"] = $this->translate;
        $page_data["admin_id"] = $this->admin_id;
        $page_data["adminData"] = $this->db->table("admin")->where("id", $this->admin_id)->get()->getRow();
        $page_data["page_title"] = $this->translate->translate($title,$this->language);
        $page_data["page_name"] = $name;
        return view("Admin/" . $landing_page, $page_data);
    }

    public function dashboard()
    {
        $this->checkAdmin();
        $citiesData = $this->db->table("cities")->get()->getResult();
        $activitiesQuery = $this->db->table("activities")->select("city_id, COUNT(*) as total_activities")->groupBy("city_id")->get()->getResult();
        $volunteersQuery = $this->db->table("volunteers")->select("city_id, COUNT(*) as total_volunteers")->groupBy("city_id")->get()->getResult();
        $activitiesPerCityMap = [];
        foreach ($activitiesQuery as $row) {$activitiesPerCityMap[$row->city_id] = $row->total_activities;}
        $volunteersPerCityMap = [];
        foreach ($volunteersQuery as $row) {$volunteersPerCityMap[$row->city_id] = $row->total_volunteers;}
        $activitiesPerCity = [];
        $volunteersPerCity = [];
        foreach ($citiesData as $city) {$activitiesPerCity[] = $activitiesPerCityMap[$city->id] ?? 0;$volunteersPerCity[] = $volunteersPerCityMap[$city->id] ?? 0;}
        $adminsCount = $this->db->table("admin")->countAllResults();
        $citiesCount = count($citiesData);
        $activitiesCount = $this->db->table("activities")->countAllResults();
        $volunteersCount = $this->db->table("volunteers")->countAllResults();
        $topVolunteers = $this->db->table("volunteer_activities")->select("volunteers.id, volunteers.name, SUM(activities.hours) as total_hours")->join("volunteers", "volunteer_activities.volunteer_id = volunteers.id")->join("activities", "volunteer_activities.activity_id = activities.id")->where("volunteer_activities.status", 2)->groupBy("volunteers.id, volunteers.name")->orderBy("total_hours", "DESC")->limit(15)->get()->getResultArray();
        $data = ["cities_data" => $citiesData,"admins" => $adminsCount,"cities" => $citiesCount,"activities" => $activitiesCount,"volunteers" => $volunteersCount,"top_volunteers" => $topVolunteers,"activitiesPerCity" => $activitiesPerCity,"volunteersPerCity" => $volunteersPerCity,];
        return $this->page("dashboard", 'dashboard', $data);
    }

    public function admins()
    {
        $this->checkAdmin();
        $entityName = "admin";
        $entityData = $this->getEntityData($entityName);
        $currentAdmin = $this->db->table($entityName)->where("id", $this->admin_id)->get()->getRow();
        $data = ["entityName" => $entityName,"entityData" => $entityData,"hidden"=> $currentAdmin->owner ?? null, "entities"=> $this->db->table($entityName)->get()->getResult(),];
        return $this->page("grid_view_one", "الإداريين", $data);
    }
    
    public function calendar()
    {
        $this->checkAdmin();
        $entityName = "activities";
        $entityData = $this->getEntityData($entityName);
        $currentAdmin = $this->db->table($entityName)->where("id", $this->admin_id)->get()->getRow();
        $data = ["entityName" => $entityName,"entityData" => $entityData,"hidden"=> $currentAdmin->owner ?? null, "entities"=> $this->db->table($entityName)->get()->getResult(),];
        return $this->page("calendar", "calendar", $data);
    }    

    
    public function volunteers()
    {
        $this->checkAdmin();
        $entityName = "volunteers";
        $entityData = $this->getEntityData($entityName);
        $data = ["entityName" => $entityName,"entityData" => $entityData,"entities" => $this->db->table($entityName)->get()->getResult(),];
        return $this->page("grid_view_one", "المتطوعين", $data);
    }

    public function cities()
    {
        $this->checkAdmin();
        $entityName = "cities";
        $entityData = $this->getEntityData($entityName);
        $data = ["entityName" => $entityName,"entityData" => $entityData,"entities" => $this->db->table($entityName)->get()->getResult(),];
        return $this->page("grid_view_one", "المدن", $data);
    }

    public function news()
    {
        $this->checkAdmin();
        $entityName = "news";
        $entityData = $this->getEntityData($entityName);
        $data = ["entityName" => $entityName,"entityData" => $entityData,"link" => "news_page","entities" => $this->db->table($entityName)->get()->getResult(),];
        return $this->page("grid_view_two", "الأخبار", $data);
    }

    public function profile()
    {
        $this->checkAdmin();
        $entityName = "admin";
        $entityData = $this->getEntityData($entityName);
        $data = ["entityName" => $entityName,"entityData" => $entityData,"entities" => $this->db->table($entityName)->get()->getResult(),];
        return $this->page("profile", "ملفي الشخصي", $data);
    }

    public function library()
    {
        $this->checkAdmin();
        $entityName = "library";
        $entityData = $this->getEntityData($entityName);
        $data = ["entityName" => $entityName,"entityData" => $entityData,"entities" => $this->db->table($entityName)->get()->getResult(),];
        return $this->page("grid_view_media", "مكتبة الوسائط", $data);
    }

    public function activities()
    {
        $this->checkAdmin();
        $entityName = "activities";
        $entityData = $this->getEntityData($entityName);
        $activities = $this->db->table($entityName)->get()->getResult();
        $cities = $this->db->table("cities")->get()->getResult();
        $cityMap = [];
        foreach ($cities as $city) 
        {
            $cityMap[$city->id] = $city->name;
        }
        foreach ($activities as &$activity) 
        {
            $activity->city_name = $cityMap[$activity->city_id] ?? "Unknown";
        }
        unset($activity);
        $data = ["entityName" => $entityName,"insert_notifications" => "1","notification_type" => $entityName,"entityData" => $entityData,"link" => "activity","link2" => "report","entities" => $activities,];
        return $this->page("grid_view_two", "النشاطات", $data);
    }

    public function volunteer_activities()
    {
        $this->checkAdmin();
        $entityName = "volunteer_activities";
        $entityData = $this->getEntityData($entityName);
        $volunteer_activities = $this->db->table($entityName)->get()->getResult();
        $cities = $this->db->table("cities")->get()->getResult();
        $cityMap = [];
        foreach ($cities as $city) 
        {
            $cityMap[$city->id] = $city->name;
        }
        $data = ["entityName" => $entityName,"entityData" => $entityData,"entities" => $volunteer_activities,];
        return $this->page("table", "طلبات التطوع", $data);
    }
    
    public function activity()
    {
        $this->checkAdmin();
        $id = $this->request->getGet("id");
        if (!$id) {
            return $this->response->setStatusCode(400)->setBody("ID is required.");
        }
        else
        {
            $activity = $this->db->table("activities")->where("id", $id)->get()->getRow();
            if (!$activity) {
                return $this->response->setStatusCode(404)->setBody("لم يتم العثور على النشاط");
            }
            $data = ["entities" => $this->db->table("activities")->where("id", $id)->get()->getResult(),"id" => $id,];
            return $this->page("activity", $activity->name, $data);
        }
    }

    public function news_page()
    {
        $this->checkAdmin();
        $entityName = "news";
        $id = $this->request->getGet("id");
        if (!$id) 
        {
            return $this->response->setStatusCode(400)->setBody("ID is required.");
        }
        else
        {
            $news = $this->db->table($entityName)->where("id", $id)->get()->getRow();
            if (!$news) 
            {
                return $this->response->setStatusCode(404)->setBody("Activity not found.");
            }
            $data = ["entityName" => $entityName,"entities" => $this->db->table($entityName)->where("id", $id)->get()->getResult(),"id" => $id,];
            return $this->page("news_page", $news->name, $data);
        }
    }

    public function certificate()
    {
        $this->checkAdmin();
        $entityName = "volunteer_activities";
        $id = $this->request->getGet("id");
        if (!$id) 
        {
            return $this->response->setStatusCode(400)->setBody("ID is required.");
        }
        else
        {
            $generator = new \Picqer\src\BarcodeGeneratorPNG();
            $barcode = $generator->getBarcode($id, $generator::TYPE_CODE_128);
            $image ='<img style="padding-left:30px" src="data:image/png;base64,' .base64_encode($barcode) .'" />';
            $data = ["barcode" => $image,"entityName" => $entityName,"entities" => $this->db->table($entityName)->where("id", $id)->get()->getResult(),"id" => $id,];
            return $this->page("certificate", "شهادة إتمام نشاط", $data, "blank");
        }
    }

    public function public_certificate()
    {
        $this->checkAdmin();
        $entityName = "volunteer_activities";
        $id = $this->request->getGet("id");
        if (!$id) 
        {
            return $this->response->setStatusCode(400)->setBody("ID is required.");
        }
        else
        {
            $generator = new \Picqer\src\BarcodeGeneratorPNG();
            $barcode = $generator->getBarcode($id, $generator::TYPE_CODE_128);
            $image = '<img style="padding-left:30px" src="data:image/png;base64,' .base64_encode($barcode) .'" />';
            $data = ["barcode" => $image,"entityName" => $entityName,"entities" => $this->db->table($entityName)->where("id", $id)->get()->getResult(),"id" => $id,];
            return $this->page("public_certificate","شهادة إتمام نشاط",$data,"blank");
        }
    }

    public function report()
    {
        $this->checkAdmin();
        $entityName = "volunteer_activities";
        $id = $this->request->getGet("id");
        if (!$id) 
        {
            return $this->response->setStatusCode(400)->setBody("ID is required.");
        }
        else
        {
            $data = ["entityName" => $entityName,"entities" => $this->db->table($entityName)->where("activity_id", $id)->get()->getResult(),"id" => $id,];
            return $this->page("report", "كشف بيانات المتطوعين", $data, "blank");
        }
    }

    private function loadMessages()
    {
        $path = WRITEPATH . 'config/messages.json';
        if (!file_exists($path)) 
        {
            throw new \Exception("Messages file not found.");
        }
        $json = file_get_contents($path);
        return json_decode($json, true);
    }
    
    private function prepareMessage($template, $replacements)
    {
        foreach ($replacements as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        return $template;
    }

    public function updateStatus()
    {
        $request = $this->request->getJSON();
        if (!isset($request->id) || !isset($request->status)) 
        {
            return $this->response->setJSON(["success" => false, "message" => "Invalid input"]);
        }
        $id = $request->id;
        $status = $request->status;
        $update = $this->db->table("volunteer_activities")->where("id", $id)->update(["status" => $status]);
        if (!$update) 
        {
            return $this->response->setJSON(["success" => false, "message" => "Failed to update status"]);
        }
        $volunteerActivity = $this->db->table("volunteer_activities")->where("id", $id)->get()->getRow();
        $volunteer = $this->db->table("volunteers")->where("id", $volunteerActivity->volunteer_id)->get()->getRow();
        $activity = $this->db->table("activities")->where("id", $volunteerActivity->activity_id)->get()->getRow();
        $city = $this->db->table("cities")->where("id", $activity->city_id)->get()->getRow();
        $recipients = [$volunteer->phone];
        $messagesData = $this->loadMessages();
        $statusMessages = $messagesData['status_messages'];
        if (!isset($statusMessages[$status])) 
        {
            return $this->response->setJSON(["success" => false, "message" => "No message defined for this status"]);
        }
        $message = $this->prepareMessage($statusMessages[$status], ['activity_name' => $activity->name,'activity_date' => $activity->date_from,'activity_organisation' => $activity->organisation,'city_name' => $city->name,'activity_required_files' => $activity->required_files ?? 'لا يوجد']);
        $results = $this->notificationsender->sendText($recipients, $message);
        return $this->response->setJSON(["success" => true]);
    }

    public function add_entity()
    {
        $jsonData = $this->request->getJSON();
        $tableName = $jsonData->table ?? null;
        $insert_notifications = $jsonData->insert_notifications ?? null;
        $notification_type = $jsonData->notification_type ?? null;
        $entityFields = $jsonData->fields_entity ?? null;
        if (!$tableName) {return $this->response->setJSON(["status" => "error","message" => "Invalid table name.",]);}
        try {
            $filePath = null;
            $entityFields = (array) ($entityFields ?? []);
            if (isset($entityFields['password'])) {
                $entityFields['password'] = password_hash($entityFields['password'], PASSWORD_BCRYPT);
            }
            if (isset($entityFields["file"])) {
                $base64File = $entityFields["file"];
                $originalFilename = $entityFields["original_filename"] ?? "unknown_file";
                unset($entityFields["file"], $entityFields["original_filename"]);
                $this->addDynamicFieldsFromJson($tableName, $entityFields, $originalFilename);
                if (empty($entityFields)) {
                    $entityFields = ["id" => null];
                }
                $this->data->setFieldsAndPrimaryKey(array_keys($entityFields), "id")->table($tableName);
                $insertId = $this->data->insertData($entityFields);
                if (preg_match("/^data:([a-zA-Z0-9\/\+]+);base64,/", $base64File, $type)) {
                    $base64File = substr($base64File, strpos($base64File, ",") + 1);
                    $mimeType = strtolower($type[1]);
                    $decodedFile = base64_decode($base64File);
                    if ($decodedFile === false) {
                        throw new \RuntimeException("Base64 decoding failed.");
                    }
                    $fileExtension = explode("/", $mimeType)[1] ?? "";
                    if (!$fileExtension) {
                        throw new \RuntimeException("Failed to determine file extension.");
                    }
                    $uploadDir = FCPATH . "uploads/" . $tableName . "_files/";
                    $filePath = $uploadDir . $insertId . "." . $fileExtension;
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    if (!file_put_contents($filePath, $decodedFile)) {
                        throw new \RuntimeException("Failed to save the file.");
                    }
                } else {
                    throw new \RuntimeException("Invalid file format.");
                }
            } elseif ($entityFields) {
                $this->addDynamicFieldsFromJson($tableName, $entityFields);
                $this->data->setFieldsAndPrimaryKey(array_keys($entityFields), "id")->table($tableName);
                $insertId = $this->data->insertData($entityFields);
            } else {
                return $this->response->setJSON(["status" => "error","message" => "No fields or file provided.",]);
            }
            if ($insert_notifications == 1 && $notification_type == "activities") {
                $message = "تم نشر نشاط جديد بعنوان : " . $entityFields["name"] . " , سجل دخولك الى المنصة و اكتشف التفاصيل !";
                $phoneNumbers = $this->db->table("volunteers")->select("phone")->get()->getResultArray();
                $recipients = array_column($phoneNumbers, "phone");
                $this->notificationsender->sendTextHandler($recipients, $message);
            }
            if ($insert_notifications == 1 && $notification_type == "news") {
                $message = "تم نشر خبر جديد : " . $entityFields["name"] . " , سجل دخولك الى المنصة و اكتشف التفاصيل !";
                $phoneNumbers = $this->db->table("volunteers")->select("phone")->get()->getResultArray();
                $recipients = array_column($phoneNumbers, "phone");
                $this->notificationsender->sendTextHandler($recipients, $message);
            }
            return $this->response->setJSON(["status" => "success","id" => $insertId ?? null,"message" => "Entity added successfully.","file_path" => $filePath,]);
        } catch (\Exception $e) {
            return $this->response->setJSON(["status" => "error","message" => $e->getMessage(),]);
        }
    }

    private function addDynamicFieldsFromJson(string $tableName,array &$entityFields,string $filename = ""): void 
    {
        $configFile = APPPATH . "Config/additional_fields.json";
        if (!file_exists($configFile)) 
        {
            throw new \RuntimeException("Configuration file not found.");
        }
        $configData = json_decode(file_get_contents($configFile), true);
        if (!isset($configData[$tableName])) 
        {
            return;
        }
        foreach ($configData[$tableName] as $field => $value) 
        {
            if ($value === "{{date_now}}") 
            {
                $value = date("Y-m-d H:i:s");
            }
            if ($value === "{{login_user_id}}") 
            {
                $value = $this->admin_id;
            }
            if ($value === "{{filename}}") 
            {
                $value = $filename;
            }
            $entityFields[$field] = $value;
        }
    }

    public function delete_entity()
    {
        $jsonData = $this->request->getJSON();
        $tableName = $jsonData->table ?? null;
        $entityId = $jsonData->id_entity ?? null;
        if (!$tableName || !$entityId) 
        {
            return $this->response->setJSON(["status" => "error","message" => "Table name and entity ID are required.",]);
        }
        try {
            $this->data->table($tableName);
            $isDeleted = $this->data->deleteData($entityId);
            $directoryPath = FCPATH . "uploads/{$tableName}_files/";
            $files = glob($directoryPath . "{$entityId}.*");
            if ($isDeleted) {
                foreach ($files as $filePath) 
                {
                    if (is_file($filePath)) 
                    {
                        unlink($filePath);
                    }
                }
                return $this->response->setJSON(["status" => "success","message" => $tableName,]);
            } else 
            {
                return $this->response->setJSON(["status" => "error","message" => "حدثت مشكلة أثناء حذف البيانات",]);
            }
        } catch (\Exception $e) 
        {
            return $this->response->setJSON(["status" => "error","message" =>"An error occurred during deletion: " . $e->getMessage(),]);
        }
    }

    public function updateCertificateStatus()
    {
        $json = $this->request->getJSON();
        $id = $json->id;
        $field = $json->field;
        $value = $json->value;
        $db = \Config\Database::connect();
        $builder = $db->table("volunteer_activities");
        $builder->where("id", $id)->update([$field => $value]);
        return $this->response->setJSON(["success" => true]);
    }

    public function bulk_delete()
    {
        $jsonData = $this->request->getJSON();
        $tableName = $jsonData->table ?? null;
        $ids = $jsonData->ids ?? null;
        if (!$tableName || empty($ids)) 
        {
            return $this->response->setJSON(["status" => "error","message" => "Table name and IDs are required.",]);
        }
        try 
        {
            $this->data->table($tableName);
            $this->data->deleteBatch($ids, "id");
            return $this->response->setJSON(["status" => "success"]);
        } catch (\Exception $e) 
        {
            return $this->response->setJSON(["status" => "error"]);
        }
    }

    public function update_post_entity()
    {
        $postData = $this->request->getPost();
        return $this->update_entity($postData);
    }

    public function update_json_entity()
    {
        $postData = $this->request->getJSON(true);
        return $this->update_entity($postData);
    }

    public function update_entity($postData)
    {
        $tableName = $postData["table"] ?? null;
        $entityId = $postData["id_entity"] ?? null;
        $entityFields = $postData;
        unset($entityFields["table"], $entityFields["id_entity"]);
        if (!$tableName || !$entityId || empty($entityFields)) 
        {
            return $this->response->setJSON(["status" => "error","message" => "Table name, entity ID, and fields are required.",]);
        }
        try 
        {
            $uploadedFile = null;
            foreach ($this->request->getFiles() as $inputName => $file) 
            {
                if ($file->isValid() && !$file->hasMoved()) 
                {
                    $uploadedFile = $file;
                    break;
                }
            }
            if ($uploadedFile) 
            {
                $uploadDir = FCPATH . "uploads/" . $tableName . "_files/";
                if (!is_dir($uploadDir)){mkdir($uploadDir, 0777, true);}
                $filePattern = $uploadDir . $entityId . ".*";
                foreach (glob($filePattern) as $existingFile) {if (file_exists($existingFile)) {unlink($existingFile);}}
                $fileExtension = $uploadedFile->getClientExtension();
                $fileName = $entityId . "." . $fileExtension;
                $uploadedFile->move($uploadDir, $fileName);
            }
            foreach ($entityFields as $key => $value) 
            {
                if (is_array($value)){$entityFields[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);}
                if ($key === 'password') {if (!empty($value)) {$entityFields[$key] = password_hash($value, PASSWORD_BCRYPT);} else {unset($entityFields[$key]);}}
            }
            $this->data->table($tableName);
            $isUpdated = $this->data->updateData($entityId, $entityFields);
            if ($isUpdated) 
            {
                return $this->response->setJSON(["status" => "success","message" => "تم تحديث البيانات بنجاح",]);
            } 
            else
            {
                return $this->response->setJSON(["status" => "error","message" => "حدثت مشكلة أثناء تحديث البيانات",]);
            }
    
        } catch (\Exception $e) 
        {
            return $this->response->setJSON(["status" => "error","message" => $e->getMessage(),]);
        }
    }

    public function getEntityData($tablename)
    {
        $cities = $this->db->table("cities")->select("id, name")->get()->getResultArray();
        $citiesOptions = [];
        foreach ($cities as $city) 
        {
            $citiesOptions[$city["id"]] = $city["name"];
        }
        $activities = $this->db->table("activities")->select("id, name")->get()->getResultArray();
        $ActivitiesOptions = ["0" => "بلا تصنيف"];
        foreach ($activities as $activity) 
        {
            $ActivitiesOptions[$activity["id"]] = $activity["name"];
        }
        $genders = $this->db->table("genders")->select("id, name")->get()->getResultArray();
        $GendersOptions = [];
        foreach ($genders as $gender) 
        {
            $GendersOptions[$gender["id"]] = $gender["name"];
        }
        $EntityData = [
            "admin" => [
                "name" => [
                    "id" => "name",
                    "placeholder" => "الاسم",
                    "type" => "text",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "email" => [
                    "id" => "email",
                    "placeholder" => "البريد الإلكتروني",
                    "type" => "email",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "username" => [
                    "id" => "username",
                    "placeholder" => "اسم المستخدم",
                    "type" => "text",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "password" => [
                    "id" => "password",
                    "placeholder" => "كلمة المرور",
                    "type" => "password",
                    "class_id" => "col-md-6",
                    "required" => false,
                    "empty" => true,
                ],
                "phone" => [
                    "id" => "phone",
                    "placeholder" => "رقم الهاتف",
                    "type" => "phone",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "language" => [
                    "id" => "language",
                    "placeholder" => "اللغة",
                    "type" => "select",
                    "class_id" => "col-md-6",
                    "options" => [
                        "ar" => "اللغة العربية",
                        "en" => "English Language",
                    ],
                    "required" => true,
                ],
                "image" => [
                    "id" => "image",
                    "placeholder" => "صورة شخصية",
                    "type" => "file",
                    "class_id" => "col-md-6",
                    "required" => false,
                ],
                "owner" => [
                    "id" => "owner",
                    "placeholder" => "الصلاحية",
                    "type" => "select",
                    "class_id" => "col-md-6",
                    "options" => [
                        "0" => "إداري",
                        "1" => "مدير المنظومة",
                    ],
                    "required" => true,
                ],
            ],
            "cities" => [
                "name" => [
                    "id" => "name",
                    "placeholder" => "اسم المدينة",
                    "type" => "text",
                    "class_id" => "col-md-12",
                    "required" => true,
                ],
            ],
            "library" => [
                "file" => [
                    "id" => "file",
                    "placeholder" => "اختر ملف الوسائط",
                    "type" => "file",
                    "required" => true,
                ],
            ],
            "news" => [
                "name" => [
                    "id" => "name",
                    "placeholder" => "عنوان المنشور",
                    "type" => "text",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "activity_id" => [
                    "id" => "activity_id",
                    "placeholder" => "التصنيف",
                    "type" => "select",
                    "class_id" => "col-md-6",
                    "options" => $ActivitiesOptions,
                    "required" => true,
                ],
                "post_date" => [
                    "id" => "post_date",
                    "placeholder" => "تاريخ النشر",
                    "type" => "date",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "post_thumbnail" => [
                    "id" => "post_thumbnail",
                    "placeholder" => "صورة المنشور",
                    "type" => "file",
                    "class_id" => "col-md-6",
                    "required" => false,
                ],
                "post_content" => [
                    "id" => "post_content",
                    "placeholder" => "تفاصيل المنشور",
                    "type" => "textarea",
                    "class_id" => "col-md-12",
                    "required" => true,
                ],
            ],
            "volunteers" => [
                "name" => [
                    "id" => "name",
                    "placeholder" => "الاسم",
                    "type" => "text",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "image" => [
                    "id" => "image",
                    "placeholder" => "صورة شخصية",
                    "type" => "file",
                    "class_id" => "col-md-6",
                    "required" => false,
                ],
                "email" => [
                    "id" => "email",
                    "placeholder" => "البريد الإلكتروني",
                    "type" => "email",
                    "class_id" => "col-md-6",
                    "required" => false,
                ],
                "birthdate" => [
                    "id" => "birthdate",
                    "placeholder" => "تاريخ الميلاد",
                    "type" => "text",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "gender" => [
                    "id" => "gender",
                    "placeholder" => "الجنس",
                    "type" => "select",
                    "class_id" => "col-md-6",
                    "options" => $GendersOptions,
                    "required" => true,
                ],
                "username" => [
                    "id" => "username",
                    "placeholder" => "اسم المستخدم",
                    "type" => "text",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "address" => [
                    "id" => "address",
                    "placeholder" => "العنوان",
                    "type" => "text",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "password" => [
                    "id" => "password",
                    "placeholder" => "كلمة المرور",
                    "type" => "password",
                    "class_id" => "col-md-6",
                    "required" => false,
                ],
                "phone" => [
                    "id" => "phone",
                    "placeholder" => "رقم الهاتف",
                    "type" => "phone",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "identity" => [
                    "id" => "identity",
                    "placeholder" => "التعريف الشخصي",
                    "type" => "text",
                    "class_id" => "col-md-6",
                    "required" => false,
                ],
                "academic_value" => [
                    "id" => "academic_value",
                    "placeholder" => "المؤهل العلمي / التخصص",
                    "type" => "text",
                    "class_id" => "col-md-12",
                    "required" => false,
                ],
                "hobbies" => [
                    "id" => "hobbies",
                    "placeholder" => "الهوايات",
                    "type" => "text",
                    "class_id" => "col-md-12",
                    "required" => false,
                ],
                "language" => [
                    "id" => "language",
                    "placeholder" => "اللغة",
                    "type" => "select",
                    "class_id" => "col-md-6",
                    "options" => [
                        "ar" => "اللغة العربية",
                        "en" => "English Language",
                    ],
                    "required" => true,
                ],
                "city_id" => [
                    "id" => "city_id",
                    "placeholder" => "المدينة",
                    "type" => "select",
                    "class_id" => "col-md-6",
                    "options" => $citiesOptions,
                    "required" => true,
                ],
            ],
            "activities" => [
                "name" => [
                    "id" => "name",
                    "placeholder" => "عنوان النشاط",
                    "type" => "text",
                    "class_id" => "col-md-12",
                    "required" => true,
                ],
                "organisation" => [
                    "id" => "organisation",
                    "placeholder" => "اسم المنظمة",
                    "type" => "text",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "city_id" => [
                    "id" => "city_id",
                    "type" => "select",
                    "class_id" => "col-md-6",
                    "placeholder" => "المدينة",
                    "options" => $citiesOptions,
                    "required" => true,
                ],
                "date_from" => [
                    "id" => "date_from",
                    "placeholder" => "تاريخ بدء النشاط",
                    "type" => "date",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "date_to" => [
                    "id" => "date_to",
                    "placeholder" => "تاريخ نهاية النشاط",
                    "type" => "date",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "image" => [
                    "id" => "image",
                    "placeholder" => "صورة غلاف للنشاط",
                    "type" => "file",
                    "class_id" => "col-md-6",
                    "required" => false,
                ],
                "description" => [
                    "id" => "description",
                    "placeholder" => "وصف النشاط",
                    "type" => "textarea",
                    "class_id" => "col-md-12",
                    "required" => true,
                ],
                "required_files" => [
                    "id" => "required_files",
                    "placeholder" => "الملفات المطلوبة",
                    "type" => "textarea",
                    "class_id" => "col-md-12",
                    "required" => true,
                ],
                "hours" => [
                    "id" => "hours",
                    "placeholder" => "ساعات التطوع",
                    "type" => "text",
                    "class_id" => "col-md-6",
                    "required" => true,
                ],
                "transportation" => [
                    "id" => "transportation",
                    "placeholder" => "التكفل بالمواصلات",
                    "options" => ["1", "0"],
                    "type" => "radio",
                    "class_id" => "col-md-6",
                    "required" => false,
                ],
                "residency" => [
                    "id" => "residency",
                    "placeholder" => "التكفل بالإقامة",
                    "options" => ["1", "0"],
                    "type" => "radio",
                    "class_id" => "col-md-6",
                    "required" => false,
                ],
                "expenses" => [
                    "id" => "expenses",
                    "placeholder" => "التكفل بالإعاشة",
                    "options" => ["1", "0"],
                    "type" => "radio",
                    "class_id" => "col-md-6",
                    "required" => false,
                ],
                "training" => [
                    "id" => "training",
                    "placeholder" => "التكفل بالتدريب",
                    "options" => ["1", "0"],
                    "type" => "radio",
                    "class_id" => "col-md-6",
                    "required" => false,
                ],
            ],
        ];
        return $EntityData[$tablename] ?? [];
    }
    
    public function calendar_activities()
    {
        $activities = $this->db->table("activities")->get()->getResultArray();
        $events = [];
    
        foreach ($activities as $activity) 
        {
            $events[] = [
                'title' => $activity['name'], // Correct field name
                'start' => $activity['date_from'], // Correct field name
                'end'   => date('Y-m-d', strtotime($activity['date_to'] . ' +1 day')), // To include end date
                'color' => '#304300', // Optional: static or dynamic color
                'extendedProps' => [
                    'hours'   => $activity['hours'],
                    'city_id' => $activity['city_id']
                ]
            ];
        }
    
        header('Content-Type: application/json');
        echo json_encode($events);
    }

    public function data_grap()
    {
        $jsonData = $this->request->getJSON();
        $tableName = $jsonData->table ?? null;
        $row_id = $jsonData->id_entity ?? null;
        $data = $this->db->table($tableName)->where("id", $row_id)->get()->getRow();
        return $this->response->setJSON(["status" => "success","data" => $data,]);
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        $session->setFlashdata("logout_notification", "logged_out");
        return redirect()->to(base_url());
    }
}
