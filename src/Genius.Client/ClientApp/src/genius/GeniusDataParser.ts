/**
 * This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
 * If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
 * Copyright (C) 2022 Leszek Pomianowski.
 * All Rights Reserved.
 */

import { IExpertAbout } from './interfaces/IExpertAbout';
import { IExpertProduct } from './interfaces/IExpertProduct';
import { IExpertCondition } from './interfaces/IExpertCondition';
import { IExpertRelation } from './interfaces/IExpertRelation';
import { IExpertRelations } from './interfaces/IExpertRelations';
import { ExpertAbout } from './ExpertAbout';
import { ExpertProduct } from './ExpertProduct';
import { ExpertCondition } from './ExpertCondition';
import { ExpertRelation } from './ExpertRelation';
import { ExpertRelations } from './ExpertRelations';

/**
 * Contains static methods that cast objects to the expected interfaces.
 */

export class GeniusDataParser {
  public static buildFormData(data: object): FormData {
    const formData = new FormData();

    for (let key in data) {
      formData.append(key, data[key]);
    }

    return formData;
  }

  public static fetchSystemAboutObject(dataObject: any): IExpertAbout {
    return new ExpertAbout(
      dataObject?.id ?? 0,
      dataObject?.products ?? 0,
      dataObject?.conditions ?? 0,
      dataObject?.relations ?? 0,
    );
  }

  public static fetchConditionObject(dataObject: any): IExpertCondition {
    return new ExpertCondition(
      dataObject.id ?? 0,
      dataObject.systemId ?? 0,
      dataObject.name ?? '',
      dataObject.description ?? '',
    );
  }

  public static fetchProductObject(dataObject: any): IExpertProduct {
    return new ExpertProduct(
      dataObject.id ?? 0,
      dataObject.systemId ?? 0,
      dataObject.name ?? '',
      dataObject.description ?? '',
      dataObject.notes ?? '',
    );
  }

  public static fetchRelationsObject(dataObject: any): IExpertRelations {
    return new ExpertRelations(
      dataObject.id ?? 0,
      dataObject.systemId ?? 0,
      dataObject.confirming ?? [],
      dataObject.negating ?? [],
      dataObject.indifferent ?? [],
    );
  }

  public static fetchRelationObject(dataObject: any): IExpertRelation {
    return new ExpertRelation(
      dataObject.id ?? 0,
      dataObject.systemId ?? 0,
      dataObject.conditionId ?? 0,
      dataObject.productId ?? 0,
      dataObject.weight ?? 100,
    );
  }
}
