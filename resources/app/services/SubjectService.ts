import ModelService from "@/services/ModelService";

export default class SubjectService extends ModelService {

    constructor() {
        super();
        this.url = '/subjects';
    }

}
