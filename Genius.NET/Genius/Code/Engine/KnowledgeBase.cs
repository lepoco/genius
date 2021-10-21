// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System.Collections.Generic;
using System.Linq;

namespace Genius.Code.Engine
{
    public enum KnowledgeBaseType
    {
        Conditional = 0,
        Fuzzy
    }

    /// <summary>
    /// Knowledge base containing <see cref="Engine.Product"/>'s and <see cref="Engine.Condition"/>'s.
    /// </summary>
    public sealed class KnowledgeBase : Engine.Interfaces.IExpertDatabase
    {
        private int
            _highestConditionId = 0,
            _highestProductId = 0;

        private KnowledgeBaseType? _type;
        private List<Engine.Product> _products;
        private List<Engine.Condition> _conditions;
        private List<Engine.Reference> _references;

        /// <summary>
        /// Gets or sets information on what type the current knowledge base is.
        /// </summary>
        public KnowledgeBaseType? Type
        {
            get => this._type == null ? KnowledgeBaseType.Conditional : this._type;
            set => this._type = value;
        }

        /// <summary>
        /// Gets or sets list of all <see cref="Engine.Product"/>'s belonging to the knowledge base.
        /// </summary>
        public List<Engine.Product> Products
        {
            get
            {
                if (this._products == null) this._products = new List<Engine.Product>();
                return this._products;
            }
            set => this._products = value;
        }

        /// <summary>
        /// Gets or sets list of all <see cref="Engine.Condition"/>'s belonging to the knowledge base.
        /// </summary>
        public List<Engine.Condition> Conditions
        {
            get
            {
                if (this._conditions == null) this._conditions = new List<Engine.Condition>();
                return this._conditions;
            }
            set => this._conditions = value;
        }


        /// <summary>
        /// Gets or sets a list of <see cref="Engine.Reference"/>'s that confirm, negate, or neutralize the result. <see cref="Engine.Reference"/> is a logical link of <see cref="Engine.Condition"/>.
        /// </summary>
        public List<Engine.Reference> References
        {
            get
            {
                if (this._conditions == null) this._references = new List<Engine.Reference>();
                return this._references;
            }
            set => this._references = value;
        }

        /// <summary>
        /// Adds a new <see cref="Engine.System"/> condition to the list available under <see cref="Engine.Product"/>
        /// </summary>
        /// <param name="name">New product name</param>
        /// <param name="description">New product description</param>
        public void AddProduct(string name, string description = null)
        {
            if (this._products == null)
            {
                this._products = new List<Engine.Product> { new Engine.Product { ID = this._highestProductId++, Name = name, Description = description } };
            }
            else
            {
                int id = 0;
                foreach (Engine.Product product in this._products)
                    if (product.ID >= id)
                        id = product.ID + 1;

                this._products.Add(new Engine.Product { ID = id, Name = name, Description = description });
            }
        }

        /// <summary>
        /// Adds a new <see cref="Engine.System"/> result to the list available under <see cref="KnowledgeBase.Products"/>
        /// </summary>
        /// <param name="product">Object representing the newly added <see cref="Engine.Product"/></param>
        public void AddProduct(Engine.Product product)
        {
            if (this._products == null)
                this._products = new List<Engine.Product> { product };
            else
                this._products.Add(product);
        }

        /// <summary>
        /// Returns <see cref="Engine.Product"/> being the element of the <see cref="KnowledgeBase.Products"/> by its ID.
        /// </summary>
        /// <param name="id">The identifier of the <see cref="Engine.Product"/> being the element of <see cref="KnowledgeBase.Products"/></param>
        /// <returns><see cref="Engine.Product"/> or <see langword="null"/> if it doesn't exist.</returns>
        public Engine.Product? GetProductById(int id)
        {
            //Lambda
            return this._products.Find(x => x.ID == id);
        }

        /// <summary>
        /// Returns <see cref="Engine.Product"/> being the element of the <see cref="KnowledgeBase.Products"/> by its name.
        /// </summary>
        /// <param name="id">The identifier of the <see cref="Engine.Product"/> being the element of <see cref="KnowledgeBase.Products"/></param>
        /// <returns><see cref="Engine.Product"/> or <see langword="null"/> if it doesn't exist.</returns>
        public Engine.Product? GetProductByName(string name)
        {
            //Lambda
            return this._products.Find(x => x.Name == name);
        }

        /// <summary>
        /// Adds a new <see cref="Engine.System"/> condition to the list available under <see cref="KnowledgeBase.Conditions"/>
        /// </summary>
        /// <param name="name">New condition name</param>
        /// <param name="description">New condition description</param>
        public void AddCondition(string name, string description = null)
        {
            if (this._conditions == null)
            {
                this._conditions = new List<Engine.Condition> { new Engine.Condition { ID = 0, Name = name, Description = description } };
            }
            else
            {
                int id = 0;
                foreach (Engine.Condition condition in this._conditions)
                    if (condition.ID >= id)
                        id = condition.ID + 1;

                this._conditions.Add(new Engine.Condition { ID = id, Name = name, Description = description });
            }
        }

        /// <summary>
        /// Adds a new <see cref="Engine.System"/> condition to the list available under <see cref="Engine.Condition"/>
        /// </summary>
        /// <param name="condition">Object representing the newly added <see cref="Engine.Condition"/></param>
        public void AddCondition(Engine.Condition condition)
        {
            if (this._conditions == null)
                this._conditions = new List<Engine.Condition> { condition };
            else
                this._conditions.Add(condition);
        }

        /// <summary>
        /// Returns <see cref="Engine.Condition"/> being the element of the <see cref="KnowledgeBase.Conditions"/> by its ID.
        /// </summary>
        /// <param name="id">The identifier of the <see cref="Engine.Condition"/> being the element of <see cref="KnowledgeBase.Conditions"/></param>
        /// <returns><see cref="Engine.Condition"/> or <see langword="null"/> if it doesn't exist.</returns>
        public Engine.Condition? GetConditionById(int id)
        {
            //Lambda
            return this._conditions.Find(x => x.ID == id);
        }

        /// <summary>
        /// Returns <see cref="Engine.Condition"/> being the element of the <see cref="KnowledgeBase.Conditions"/> by its name.
        /// </summary>
        /// <param name="id">The identifier of the <see cref="Engine.Condition"/> being the element of <see cref="KnowledgeBase.Conditions"/></param>
        /// <returns><see cref="Engine.Condition"/> or <see langword="null"/> if it doesn't exist.</returns>
        public Engine.Condition? GetConditionByName(string name)
        {
            //Lambda
            return this._conditions.Find(x => x.Name == name);
        }

        /// <summary>
        /// Adds a new <see cref="Engine.System"/> reference to the list available under <see cref="Engine.Reference"/>
        /// </summary>
        /// <param name="reference">Object representing the newly added <see cref="Engine.Reference"/></param>
        public void AddReference(Engine.Reference reference)
        {
            if (this._references == null)
                this._references = new List<Engine.Reference> { reference };
            else
                this._references.Add(reference);
        }


        /// <summary>
        /// Sorts all <see cref="Engine.Reference"/> belonging to <see cref="KnowledgeBase.References"/> based on their target <see cref="Engine.Product"/> name.
        /// </summary>
        public void SortReferencesByProduct()
        {
            this._references = this._references.OrderBy(o => o.ProductId).ToList();
        }
    }
}
