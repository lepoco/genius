import IExpertCondition from './../interfaces/IExpertCondition';

export default class ExpertCondition implements IExpertCondition {
  id?: number = 0;
  name?: string = '';

  constructor(id: number, name: string = '') {
    this.id = id;
    this.name = name;
  }
}
