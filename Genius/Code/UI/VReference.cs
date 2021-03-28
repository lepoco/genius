// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

namespace Genius.Code.UI
{
    public sealed class VReference : Code.Engine.Parents.ExpertReference
    {
        private Code.Engine.Product _localProduct;
        private Code.Engine.Condition _localCondition;

        public string ProductName
        {
            get
            {
                if (this._localProduct == null)
                    this.GetLocalProduct();

                if (this._localProduct == null)
                    return "ERR. Unable to find product with id: " + this.ProductId;
                else
                    return this._localProduct.Name;
            }
        }

        public string ConditionName
        {
            get
            {
                if (this._localCondition == null)
                    this.GetLocalCondition();

                if (this._localCondition == null)
                    return "ERR. Unable to find condition with id: " + this.ConditionId;
                else
                    return this._localCondition.Name;
            }
        }

        public string ProductCastingDirection => this.ProductName + " < " + this.ConditionName;

        public void Cast(Code.Engine.Reference referenceSource)
        {
            this.ProductId = referenceSource.ProductId;
            this.ConditionId = referenceSource.ConditionId;
            this.Exclusions = referenceSource.Exclusions;
            this.Requirements = referenceSource.Requirements;
            this.Fulfil = referenceSource.Fulfil;
            this.Weight = referenceSource.Weight;
        }

        private void GetLocalProduct()
        {
            this._localProduct = Code.GH.CurrentExpertSystem.KnowledgeBase.GetProductById(this.ProductId);
        }

        private void GetLocalCondition()
        {
            this._localCondition = Code.GH.CurrentExpertSystem.KnowledgeBase.GetConditionById(this.ConditionId);
        }
    }
}
