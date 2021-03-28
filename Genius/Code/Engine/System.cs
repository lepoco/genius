// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

namespace Genius.Code.Engine
{
    public sealed class System : Interfaces.IExpertSystem
    {
        private string
            _name,
            _description,
            _question;

        private KnowledgeBase _knowledgeBase;
        
        /// <summary>
        /// Gets or sets the human-readable <see cref="Engine.System"/> name.
        /// <para>For example, it could be "<c>Expert system supporting the diagnosis of diseases</c>".</para>
        /// </summary>
        public string Name
        {
            get => this._name == null ? string.Empty : this._name;
            set => this._name = value;
        }

        /// <summary>
        /// Gets or sets the human-readable <see cref="Engine.System"/> description.
        /// <para>For example, it could be "<c>A computer program designed together with the medical university to aid in the diagnosis of diseases</c>".</para>
        /// </summary>
        public string Description
        {
            get => this._description == null ? string.Empty : this._description;
            set => this._description = value;
        }

        /// <summary>
        /// Gets or sets the human-readable <see cref="Engine.System"/> description.
        /// <para>For example, it could be "<c>A computer program designed together with the medical university to aid in the diagnosis of diseases</c>".</para>
        /// </summary>
        public string Question
        {
            get => this._question == null ? string.Empty : this._question;
            set => this._question = value;
        }

        /// <summary>
        /// Gets or sets database containing all data of a given expert system.
        /// </summary>
        public KnowledgeBase KnowledgeBase
        {
            get
            {
                if(this._knowledgeBase == null) this._knowledgeBase = new KnowledgeBase();
                return this._knowledgeBase;
            }
            set => this._knowledgeBase = value;
        }
    }
}
