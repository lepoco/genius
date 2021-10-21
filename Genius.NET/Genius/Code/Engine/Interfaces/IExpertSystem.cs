// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

namespace Genius.Code.Engine.Interfaces
{
    public interface IExpertSystem
    {
        public string Name { get; set; }
        public KnowledgeBase KnowledgeBase { get; set; }
    }
}
